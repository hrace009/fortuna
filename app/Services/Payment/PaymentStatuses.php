<?php

namespace App\Services\Payment;

class PaymentStatuses
{
    private $statusesList = [
        0 => 'pending',
        1 => 'pending',
        2 => 'analysis',
        3 => 'approved',
        4 => 'approved',
        5 => 'dispute',
        6 => 'refunded',
        7 => 'cancelled',
        8 => 'chargedback',
        9 => 'contestation',
        'pending' => 'pending',
        'approved' => 'approved',
        'authorized' => 'authorized',
        'in_process' => 'analysis',
        'in_mediation' => 'dispute',
        'canceled' => 'canceled',
        'cancelled' => 'canceled',
        'refunded' => 'refunded',
        'charged_back' => 'chargeback',
        'analysis' => 'analysis',
        'paid' => 'approved',
        'completed' => 'approved',
        'refunded' => 'refunded',
        'chargeback' => 'chargeback',
        'reversed' => 'dispute',
        'reserved' => 'reserved',
    ];

    private static $colorsList = [
        'pending' => 'warning',
        'pending' => 'warning',
        'analysis' => 'warning',
        'approved' => 'success',
        'dispute' => 'danger',
        'refunded' => 'danger',
        'canceled' => 'danger',
        'chargedback' => 'danger',
        'contestation' => 'danger',

    ];

    /**
     * Standardize the given payment status.
     *
     * @param $status
     * @return mixed|null Return status string or null if key doesn't exist.
     */
    public function standardize($status)
    {
        if (array_key_exists($status, $this->statusesList)) {
            return $this->statusesList[$status];
        }
    }

    /**
     * Status color.
     *
     * @param $status
     * @return mixed|null
     */
    public static function colorize($status)
    {
        if (array_key_exists($status, self::$colorsList)) {
            return self::$colorsList[$status];
        }
    }
}
