<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;

class SendEmailConfirmation
{
    /**
     * Handle the event.
     *
     * @param object $event
     *
     * @return void
     */
    public function handle(Registered $register)
    {
        activity()->causedBy($register->user)->log('E-mail de confirmação enviado.');

        $register->user->sendConfirmationEmail($register->user);
    }
}
