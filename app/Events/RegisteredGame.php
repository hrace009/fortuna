<?php

namespace App\Events;

class RegisteredGame
{
    /**
     * @var User
     */
    private $user;

    /**
     * Registered constructor.
     *
     * @param User $registeredUser
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}
