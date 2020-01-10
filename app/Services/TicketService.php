<?php

namespace App\Services;

use App\Events\TicketUpdated as TicketUpdatedEvent;
use App\Exceptions\TicketClosed;
use App\Models\File;
use App\Models\Ticket;
use App\Models\User;
use App\Notifications\Ticket\TicketClosed as TicketClosedNotification;
use App\Notifications\Ticket\TicketUpdated;

class TicketService
{
    public function getAllTickets()
    {
        return $this->ticket->paginate(null, 10);
    }

    /**
     * Get ticket for the user.
     *
     * @param Ticket $ticketId
     * @return void
     */
    public function getTicket($ticketId)
    {
        return $this->ticket->find($ticketId);
    }

    /**
     * Add reply to the ticket.
     *
     * @return void
     */
    public function addReply(string $message, $ticketNumber, $attachments = null)
    {
        $ticket = $this->ticket->find($ticketNumber);

        if ($ticket->isClosed()) {
            throw new TicketClosed;
        }

        $reply = $ticket->addReply([
            'message' => $message, // Turn newlines into <br /> tags
        ]);

        if (auth()->user()->isAdmin()) {
            $ticket->status_id = Ticket::WAITING_USER;
            $reply->ticket->owner->notify(new TicketUpdated($reply));
        } else {
            $ticket->status_id = Ticket::WAITING_STAFF;
            User::staff()->each(function ($user) use ($reply) {
                $user->notify(new TicketUpdated($reply));
            });
        }

        $ticket->save();

        // Emit the event
        event(new TicketUpdatedEvent($ticket));

        // Store the attachment if file POST is not null
        if ($reply && ! is_null($attachments)) {
        }

        return $ticket;
    }

    /**
     * Mark ticket as solved.
     *
     * @param [type] $ticket
     * @return void
     */
    public function closeTicket($ticketId)
    {
        $ticket = $this->ticket->find($ticketId);

        /*if (auth()->user()->isAdmin()) {
            $ticket->owner->notify(new TicketClosedNotification($ticket, auth()->user()));
        }*/

        return $this->ticket->update($ticketId, [
            'status_id' => Ticket::RESOLVED,
            'closed_at' => now(),
        ]);
    }
}
