<?php

namespace App\Services\Payment;

use Illuminate\Support\Facades\DB;
use App\Models\Payments as Transaction;
use App\Notifications\Orders\PaymentPending;
use App\Notifications\Orders\PaymentApproved;
use App\Notifications\Orders\PaymentCanceled;
use Symfony\Component\HttpFoundation\Response;
use Facades\App\Services\Payment\PaymentStatuses;

class NotificationHandler
{
    public function __construct(Transaction $transaction, string $status = '', array $data = [])
    {
        $method = 'handle'.ucfirst($status);

        if (! method_exists($this, $method)) {
            logger()->alert("Handler {$method} not found.");

            return new Response('Notification Handled', 200);
        }

        if ($transaction->transaction_status !== $status) {
            activity()
            ->performedOn($transaction)
            ->withProperties(['status' => PaymentStatuses::colorize($status)])
            ->log(__('app.order.status.system', ['status' => __("app.order.status.{$status}")]));
        }

        $transaction->update($data);

        return $this->{$method}($transaction);
    }

    /**
     * Notify the user whe the transaction status is pending.
     *
     * @param Transaction $transaction
     * @return Transaction|void
     */
    public function handlePending(Transaction $transaction)
    {
        $transaction->owner->notify(new PaymentPending($transaction));

        return $transaction;
    }

    public function handleAnalysis(Transaction $transaction)
    {
        return $transaction;
    }

    public function handleApproved(Transaction $transaction)
    {
        DB::transaction(function () use ($transaction) {
            $transaction->lockForUpdate();

            if ($transaction->hasBeenDelivered()) {
                if ((string) $transaction->transaction_status === Transaction::STATUS_RESERVED) {
                    logger()->info("CASH for Order ID [#$transaction->order_id] with RESERVED status has already been deposited to the user.");
                }

                logger()->info("CASH for Order ID [#$transaction->order_id] (attempt to deposit twice).");

                return false;
            }

            $transaction->markAsDelivered();

            activity()
                ->performedOn($transaction)
                ->withProperties(['status' => 'info'])
                ->log(
                    sprintf(
                        'Adicionado %s Cubi-Gold(s) na conta <strong>%s</strong>',
                        $transaction->formatAmount($transaction->cash_amount),
                        $transaction->game_login
                    )
                );

            $transaction->owner->notify(new PaymentApproved($transaction));

            return $transaction;
        });
    }

    public function handleReserved(Transaction $transaction)
    {
        return $transaction;
    }

    public function handleDispute(Transaction $transaction)
    {
        tap($transaction->owner)->update([
            'status' => 'FORBIDDEN',
        ]);
    }

    /**
     * Notify the user when transaction is canceled.
     */
    public function handleCanceled(Transaction $transaction)
    {
        $transaction->owner->notify(new PaymentCanceled($transaction));

        return $transaction;
    }
}
