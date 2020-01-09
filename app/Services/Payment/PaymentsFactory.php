<?php

namespace App\Services\Payment;

use InvalidArgumentException;

class PaymentsFactory
{
    public function make($paymentMethod)
    {
        switch ($paymentMethod) {
            case 'paghiper':
                return new PagHiper;
            case 'paypal':
                return new PayPal;
                case 'mercadopago':
                return new MercadoPago();
            default:
                throw new InvalidArgumentException;
        }
    }
}
