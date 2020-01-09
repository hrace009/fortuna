<?php

namespace App\Observers;

use App\Models\Ticket;

class TicketObserver
{
    /**
     * Handle the ticket "created" event.
     *
     * @param  \App\App\Models\Ticket  $ticket
     * @return void
     */
    public function created(Ticket $ticket)
    {
        activity()->log("Usuário abriu um ticket #{$ticket->track_id}.");
    }

    /**
     * Handle the ticket "updated" event.
     *
     * @param  \App\App\Models\Ticket  $ticket
     * @return void
     */
    public function updated(Ticket $ticket)
    {
        activity()->log("Usuário respondeu ao ticket #{$ticket->track_id}.");
    }

    /**
     * Handle the ticket "deleted" event.
     *
     * @param  \App\App\Models\Ticket  $ticket
     * @return void
     */
    public function deleted(Ticket $ticket)
    {
        activity()->log("Usuário marcou o ticket #{$ticket->track_id} como resolvido.");
    }
}
