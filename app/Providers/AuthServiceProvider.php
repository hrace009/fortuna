<?php

namespace App\Providers;

use App\Policies\GameRolesPolicy;
use App\Models\Roles;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\user' => \App\Policies\User\UserPolicy::class,
        \App\Models\Payments::class => \App\Policies\DonatePolicy::class,
        \App\Models\Ticket::class => \App\Policies\TicketPolicy::class,
        \App\Game\User::class => \App\Policies\GameAssociated::class,
        Roles::class => GameRolesPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::resource('ticket', \App\Policies\TicketPolicy::class);
        Gate::resource('game', \App\Policies\GameAssociated::class);

        // Game characters
        Gate::resource('roles', \App\Policies\GameRolesPolicy::class);

        // User
        Gate::define('user.update', 'App\Policies\User\UserPolicy@update');
    }
}
