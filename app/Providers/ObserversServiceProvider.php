<?php

namespace App\Providers;

use App\Observers\ActivityObserver;
use App\Observers\TicketObserver;
use App\Models\Ticket;
use Illuminate\Support\ServiceProvider;
use Spatie\Activitylog\Models\Activity;

class ObserversServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Activity::observe(ActivityObserver::class);
        Ticket::observe(TicketObserver::class);
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
