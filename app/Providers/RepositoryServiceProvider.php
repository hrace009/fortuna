<?php

namespace App\Providers;

use App\Repositories\Donate\DonateRepository;
use App\Repositories\Donate\DonateRepositoryEloquent;
use App\Repositories\Server\LogsRepository;
use App\Repositories\Server\LogsRepositoryEloquent;
use App\Repositories\Ticket\TicketRepository;
use App\Repositories\Ticket\TicketRepositoryEloquent;
use App\Repositories\User\Game\UserRepository as UserRepositoryGame;
use App\Repositories\User\Game\UserRepositoryEloquent as UserRepositoryEloquentGame;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryEloquent;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(UserRepository::class, UserRepositoryEloquent::class);
        $this->app->bind(UserRepositoryGame::class, UserRepositoryEloquentGame::class);
        $this->app->bind(TicketRepository::class, TicketRepositoryEloquent::class);
        $this->app->bind(DonateRepository::class, DonateRepositoryEloquent::class);
        $this->app->bind(LogsRepository::class, LogsRepositoryEloquent::class);
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // register
    }
}
