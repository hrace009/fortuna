<?php

namespace App\Notifications\Ticket;

use App\Models\User;
use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TicketClosed extends Notification
{
    use Queueable;

    /**
     * @var \App\Models\Ticket
     */
    protected $ticket;

    /**
     * @var \App\Models\User
     */
    protected $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Ticket $ticket, User $user)
    {
        $this->ticket = $ticket;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        //
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'author' => ['name' => $this->user->name, 'avatar' => $this->user->getAvatar()],
            'body' => ['id' => $this->ticket->track_id],
            'type' => 'fechou o ticket',
            'comment' => ['message' => ''],
            'icon' => 'la la-check',
            'path' => ['name' => 'tickets.view'],
        ];
    }
}
