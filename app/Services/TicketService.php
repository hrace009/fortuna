<?php

namespace App\Services;

use App\Events\TicketUpdated as TicketUpdatedEvent;
use App\Exceptions\TicketClosed;
use App\Models\File;
use App\Notifications\Ticket\TicketClosed as TicketClosedNotification;
use App\Notifications\Ticket\TicketCreated;
use App\Notifications\Ticket\TicketUpdated;
use App\Repositories\Ticket\TicketRepository;
use App\Models\Ticket;
use App\Models\User;

class TicketService
{
    protected $ticket;

    public function __construct(TicketRepository $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Create a new ticket.
     *
     * @param array $data
     * @retur
n void
     */
    public function createTicket(array $data)
    {
        $ticket = $this->ticket->create($data);
        $ticket->owner->notify(new TicketCreated($ticket));

        // Notify admins if ticket has been created by a normal user.
        /* User::staff()->each(function ($user) use ($ticket) {
            $user->notify(new TicketCreated($ticket));
        }); */

        return $ticket;
    }

    /**
     * Get all tickets for the given user.
     *
     * @param User $userId
     * @param int $perPage
     * @return void
     */
    public function getUserTickets(string $userId, int $perPage = 10)
    {
        return $this->ticket->paginate(
            $userId,
            $perPage
        );
    }

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
