<?php

namespace App\Services\Payment;

use App\Contracts\PaymentProvider;
use App\Models\Payments;
use App\Models\Payments as Order;
use Facades\App\Services\Payment\PaymentStatuses;
use Symfony\Component\HttpFoundation\Response;
use WebMaster\PagHiper\PagHiper as PagHiperPayment;

class PagHiper implements PaymentProvider
{
    use PaymentService;

    public function __construct()
    {
        $this->paghiper = new PagHiperPayment(config('services.paghiper.api_key'), config('services.paghiper.token'));
    }

    /**
     * Cria o pedido de pagamento no intermediador.
     *
     * @param Order $order Dados do pedido
     *
     * @return mixed
     */
    public function create($orderId)
    {
        $order = Payments::where('order_id', $orderId)->first();

        $transaction = $this->paghiper->billet()->create([
            'order_id'       => $order->order_id,
            'payer_name'     => $order->owner->name,
            'payer_email'    => $order->owner->email,
            'payer_cpf_cnpj' => $order->owner->document,
            'type_bank_slip' => 'boletoa4',
            'days_due_date'  => '3',
            'items'          => [
                [
                    'description' => $this->description($order),
                    'quantity'    => 1,
                    'item_id'     => (string) $order->order_id,
                    'price_cents' => bcmul($order->amount += 3.00, 100),
                ],
            ],
        ]);

        $billet = [
            'due_data'       => $transaction['due_date'],
            'digitable_line' => $transaction['bank_slip']['digitable_line'],
            'billet'         => $transaction['bank_slip']['url_slip_pdf'],
        ];

        $order->forceFill([
            'transaction_status' => $transaction['status'],
            'transaction_code' => $transaction['transaction_id'],
            'data' =>  collect($order->data)->put('billet', $billet),
        ])->save();

        return ['success' => true];
    }

    /**
     * et stalled order from the given user.
     * Process the Ipn (Instant Payment Notification).
     *
     * @return void
     */
    public function notification($request)
    {
        $transaction = $this->paghiper->notification()->response(
            $request['notification_id'],
            $request['idTransacao']
        );

        $paymentStatus = PaymentStatuses::standardize($transaction['status']);
        $transactionCode = $transaction['transaction_id'];

        // Get the order
        $order = Order::where('transaction_code', $request['idTransacao'])->firstOrFail();

        if (! $order) {
            logger("Pedido {$transactionCode} inexistente.");
        }

        $params = [
            'transaction_status' => $paymentStatus,
            'transaction_code'   => $transactionCode,
            'payer_name' => $transaction['payer_name'],
            'payer_email' => $transaction['payer_email'],
        ];

        new NotificationHandler($order, $paymentStatus, $params);

        return new Response;
    }
}
