<?php

namespace App\Services\Payment;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment as Sandbox;
use PayPalCheckoutSdk\Core\ProductionEnvironment as Production;

class PayPalClient
{
    /**
     * Returns PayPal HTTP client instance with environment which has access
     * credentials context. This can be used invoke PayPal API's provided the
     * credentials have the access to do so.
     */
    public static function client()
    {
        if (app()->isProduction()) {
            return new PayPalHttpClient(self::productionEnvironment());
        }

        return new PayPalHttpClient(self::sandboxEnvironment());
    }

    /**
     * Setting up and Returns PayPal SDK environment with PayPal Access credentials.
     * For demo purpose, we are using SandboxEnvironment. In production this will be
     * ProductionEnvironment.
     */
    public static function sandboxEnvironment()
    {
        $clientId = config('services.paypal.client_id_sandbox');
        $clientSecret = config('services.paypal.client_secret_sandbox');

        return new Sandbox($clientId, $clientSecret);
    }

    public static function productionEnvironment()
    {
        $clientId = config('services.paypal.client_id_live');
        $clientSecret = config('services.paypal.client_secret_live');

        return new Production($clientId, $clientSecret);
    }
}
