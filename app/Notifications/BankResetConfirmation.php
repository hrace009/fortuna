<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Game\BankReset as GameStorehouseReset;
use App\Mail\BankResetConfirmation as Mailable;

class BankResetConfirmation extends Notification
{
    use Queueable;

    /**
     * The password reset token.
     *
     * @var string
     */
    public $storehouse;

    /**
     * Create a notification instance.
     *
     * @param string $token
     *
     * @return void
     */
    public function __construct(GameStorehouseReset $storehouse)
    {
        $this->storehouse = $storehouse;
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
        $subject = sprintf('[%s] %s', config('app.name'), 'Redefinir Senha do Banqueiro');

        return (new Mailable($this->storehouse, $notifiable))->subject($subject)->to($notifiable->email);
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
