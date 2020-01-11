<?php

namespace App\Notifications\Orders;

use App\Models\Payments;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\Orders\PaymentReserved as Mailable;

class PaymentReserved extends Notification implements ShouldQueue
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
        return ['mail', 'database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $subject = sprintf('[%s] %s', config('app.name'), 'Seu Pedido foi entregue. Go, go, go!');

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
            'author' => ['name' => config('app.name'), 'avatar' => 'https://scontent.fgig4-1.fna.fbcdn.net/v/t1.0-9/27336482_1807683715964862_5429999493420463748_n.png?_nc_cat=109&_nc_oc=AQkwiTORn1xrtjW1N9_scpRWJyIjdY6kcSdBqu8x9QtBQJfgDLM_Z_8gyVvxcY7o9o2Nwnyd-6fpaLfOdY-J1kX7&_nc_ht=scontent.fgig4-1.fna&oh=30d3b28a26664d79080ae9728c8d6394&oe=5DDA3892'],
            'body'   => ['id' => $this->order->order_id],
            'type'   => 'pedido adiantado',
            'comment' => ['message' => 'Adiantamos a entrega do seu pedido, divirta-se!'],
            'icon'   => 'la-credit-card',
            'path'   => ['param' => 'id', 'name' => 'donate.view'],
        ];
    }
}
