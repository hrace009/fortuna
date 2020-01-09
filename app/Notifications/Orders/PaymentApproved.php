<?php

namespace App\Notifications\Orders;

use App\Mail\Orders\PaymentApproved as Mailable;
use App\Models\Payments;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PaymentApproved extends Notification
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
        $subject = sprintf('[%s] %s', config('app.name'), 'Seu pedido foi Aprovado. Go, go, go!');

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
        // return [
        //     'body' => "Pedido #{$this->order->order_id} aprovado.",
        //     'path' => "/doacao/historico-de-doacoes/visualizar/{$this->order->order_id}"
        // ];

        return [
            'author' => ['name' => config('app.name'), 'avatar' => ''],
            'body'   => ['id' => $this->order->order_id],
            'type'   => 'pagamento aprovado',
            'comment' => ['message' => 'Pagamento da sua doação foi aprovado. Divirta-se!'],
            'icon'   => 'la-credit-card',
            'path'   => ['param' => 'id', 'name' => 'donate.view'],
        ];
    }
}
