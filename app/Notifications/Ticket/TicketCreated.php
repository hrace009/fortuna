<?php

namespace App\Notifications\Ticket;

use App\Mail\Ticket\TicketCreated as Mailable;
use App\Models\Ticket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class TicketCreated extends Notification implements ShouldQueue
{
    use Queueable;
    
    protected $ticket;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
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
        // return $notifiable->isAdmin() ? ['database', 'broadcast'] : ['mail'];
        return ['mail'];
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
        $subject = sprintf('[%s] %s', config('app.name'), "[Ticket: {$this->ticket->track_id}] {$this->ticket->subject}");

        return (new Mailable($this->ticket))->subject($subject)->to($this->ticket->owner->email);
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
            'author' => ['name' => $this->ticket->owner->name],
            'body' => ['id' => $this->ticket->track_id],
            'type' => 'criou o ticket',
            'comment' => ['message' => $this->ticket->message],
            'icon' => 'flaticon-chat-1',
            'path' => ['name' => $notifiable->isAdmin() ? 'manage.tickets.view' : 'tickets.view'],
        ];
    }
}
