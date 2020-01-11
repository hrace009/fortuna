<?php

namespace App\Traits;

use Illuminate\Support\Carbon;
use App\Notifications\ConfirmRegistration;

trait ShouldConfirmEmail
{
    /**
     * Check if the user has verified their email address.
     *
     * @return bool
     */
    public function isConfirmed()
    {
        return ! is_null($this->email_confirmed_at);
    }

    /**
     * Mark the given user's email as confirmed.
     *
     * @return void
     */
    public function markAsConfirmed()
    {
        $this->forceFill([
            'status'                => 'CONFIRMED',
            'email_confirmed_at'    => new Carbon,
            'confirmation_token'    => null,
        ])->save();
    }

    /**
     * Send the email confirmation.
     *
     * @return void
     */
    public function sendConfirmationEmail($user)
    {
        $this->notify(new ConfirmRegistration($user));
    }
}
