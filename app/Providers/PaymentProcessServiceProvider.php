<?php

namespace App\Providers;

use App\Services\Payment\PaymentProcess;
use Illuminate\Support\ServiceProvider;

class PaymentProcessServiceProvider extends ServiceProvider
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
        $this->app->singleton('paymentprocess', function ($app) {
            return new PaymentProcess();
        });
    }
}
