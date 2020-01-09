<?php

namespace App\Providers;

use DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        \Carbon\Carbon::setLocale('pt_BR');
        /*  try {
             DB::connection('pw')->getPdo();
         } catch (\Exception $e) {
             abort(503, 'Couldn\'t connect to Game Database. Please try again later.');
         } */
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
