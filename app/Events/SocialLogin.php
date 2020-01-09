<?php

namespace App\Events;

class SocialLogin
{
    public $provider;
    public $user;

    /**
     * Create a new event instance.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  bool  $remember
     * @return void
     */
    public function __construct($user, $provider)
    {
        $this->provider = $provider;
        $this->user = $user;
    }
}
