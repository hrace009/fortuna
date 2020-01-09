<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ValidatorServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        \Validator::extend('unkiddief', 'App\Rules\Unkiddief@passes');
        \Validator::extend('valid_game_account', 'App\Rules\ValidGameAccount@passes');
        // Validator::extend('recaptcha', 'App\Rules\ReCaptcha@passes');
        \Validator::extend('multiple', 'App\Rules\Multiple@passes');
        \Validator::extend('account_exists', 'App\Rules\GameAccount@passes');
        \Validator::extend('account_owner', 'App\Rules\GameAccountOwner@passes');
        \Validator::extend('gateway_enabled', 'App\Rules\GatewayEnabled@passes');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
