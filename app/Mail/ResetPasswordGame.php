<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ResetPasswordGame extends Mailable
{
    use Queueable, SerializesModels;

    protected $token;
    protected $notifiable;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($token, $notifiable)
    {
        $this->token = $token;
        $this->notifiable = $notifiable;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.user.resetpasswordgame', [
            'url'  => url(config('app.url').'/contas-vinculadas/redefinir-senha/'.$this->token).'?name='.$this->notifiable->name,
            'user' => $this->notifiable,
            ]);
    }
}
