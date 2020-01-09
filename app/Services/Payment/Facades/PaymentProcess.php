<?php

namespace App\Services\Payment\Facades;

use Illuminate\Support\Facades\Facade;

class PaymentProcess extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'paymentprocess';
    }
}
