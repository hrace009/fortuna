<?php

namespace App\Contracts;

interface PaymentProvider
{
    /**
     * Create the transaction in the gateway.
     *
     * @return mixed
     */
    public function create($orderId);

    /**
     * Handles the IPN/WebHooks calls.
     *
     * @return mixed
     */
    public function notification($request);
}
