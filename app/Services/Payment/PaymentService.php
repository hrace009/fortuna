<?php

namespace App\Services\Payment;

use App\Models\Payments;

trait PaymentService
{
    public function description(Payments $payments)
    {
        return sprintf(
            '[%s] Doação %s - %s GOLD ("%s")',
            'PW 4Fun',
            $payments->formatMoney($payments->amount),
            $payments->formatAmount($payments->cash_amount),
            $payments->game_login
        );
    }
}
