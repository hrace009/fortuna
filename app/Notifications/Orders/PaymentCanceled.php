<?php

namespace App\Notifications\Orders;

use App\Models\Payments;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Mail\Orders\PaymentCanceled as Mailable;

class PaymentCanceled extends Notification
{
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Payments $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $subject = sprintf('[%s] %s', config('app.name'), 'Seu pedido não foi aprovado');

        return (new Mailable($this->order))->subject($subject)->to($this->order->owner->email);
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
            //
        ];
    }
}
