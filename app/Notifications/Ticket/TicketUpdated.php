<?php

namespace App\Notifications\Ticket;

use App\Models\TicketReply;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\Ticket\TicketUpdated as Mailable;

class TicketUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $ticketReply;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(TicketReply $ticketReply)
    {
        $this->ticketReply = $ticketReply;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
//        return $notifiable->isAdmin() ? ['database', 'broadcast'] : ['mail', 'database', 'broadcast'];
        return ['mail', 'database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $subject = sprintf('[%s] %s', config('app.name'), "{$this->ticketReply->ticket->owner->name}, respondemos o seu ticket #{$this->ticketReply->track_id}!");

        return (new Mailable($this->ticketReply))->subject($subject)->to($this->ticketReply->ticket->owner->email);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'author' => ['name' => $this->ticketReply->owner->name, 'avatar' => $this->ticketReply->owner->getAvatar()],
            'body' => ['id' => $this->ticketReply->track_id],
            'type' => 'respondeu',
            'comment' => ['message' => $this->ticketReply->message],
            'icon' => 'la-reply',
            'path' => ['name' => $notifiable->isAdmin() ? 'manage.tickets.view' : 'tickets.view'],
        ];
    }
}
