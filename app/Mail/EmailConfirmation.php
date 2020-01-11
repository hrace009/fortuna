<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\URL;
use Illuminate\Queue\SerializesModels;

class EmailConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Create a new message instance.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.registration.confirmation', [
            'url' => url("/login?confirmation_token={$this->user->confirmation_token}"),
            'user' => $this->user,
        ]);
    }
}
