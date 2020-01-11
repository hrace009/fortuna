<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Ticket;
use Illuminate\Auth\Access\HandlesAuthorization;

class TicketPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can view the ticket.
     *
     * @param \App\Models\User   $user
     * @param \App\Models\Ticket $ticket
     *
     * @return mixed
     */
    public function view(User $user, Ticket $ticket)
    {
        if ($user->id === $ticket->user_id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create tickets.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the ticket.
     *
     * @param  \App\Models\User  $user
     * @param  \App\App\Models\Ticket  $ticket
     * @return mixed
     */
    public function update(User $user, Ticket $ticket)
    {
        if ($user->id === $ticket->user_id || ($user->isAdmin())) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the ticket.
     *
     * @param  \App\Models\User  $user
     * @param  \App\App\Models\Ticket  $ticket
     * @return mixed
     */
    public function delete(User $user, Ticket $ticket)
    {
        return true;
    }
}
