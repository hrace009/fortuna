<?php

namespace App\Providers;

use App\Repositories\Payment\PagHiper;
use App\Repositories\Payment\PagSeguro;
use App\Repositories\Payment\PayPal;
use App\Repositories\Payment\PicPay;
use App\Repositories\TwoFactor\Authenticator;
use App\Services\RegistryManager;
use Illuminate\Support\ServiceProvider;

class PaymentServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(RegistryManager::class, function ($app) {
            $registry = new RegistryManager();

            $registry->register('paghiper', new PagHiper());
            $registry->register('paypal', new PayPal());
            $registry->register('pagseguro', new PagSeguro());
            $registry->register('picpay', new PicPay());
            $registry->register('authenticator', new Authenticator());

            return $registry;
        });
    }
}
