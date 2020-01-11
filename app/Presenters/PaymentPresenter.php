<?php

namespace App\Presenters;

use App\Models\PaymentGateway;
use Laracasts\Presenter\Presenter;
use Facades\App\Services\Payment\PaymentStatuses;

class PaymentPresenter extends Presenter
{
    /**
     * Get status represantion and related color.
     *
     * @return void
     */
    public function statusLabel()
    {
        return __("app.order.status.{$this->transaction_status}");
    }

    public function statusColor()
    {
        return PaymentStatuses::colorize($this->transaction_status);
    }

    /**
     * Get the value in cash formatted.
     *
     * @return void
     */
    public function cashFormatted()
    {
        return number_format($this->cash_amount, 0, '.', '.');
    }

    /**
     * Get the value in money formatted.
     *
     * @return void
     */
    public function moneyFormatted()
    {
        return 'R$'.number_format($this->amount, 2, ',', '.');
    }

    /**
     * Get payment's gateway name.
     *
     * @return void
     */
    public function gatewayName()
    {
        $gateway = $this->gateway;

        if (is_null($gateway)) {
            $gateway = PaymentGateway::byEnabled()->first()->slug;
        }

        return $gateway;
    }

    public function paymentMethodTypeName()
    {
        return __("app.order.paymentMethodType.{$this->payment_method_type}");
    }
}
