<?php

namespace App\Events;

use App\Game\User as GameUser;

class Associated
{
    /**
     * @var User
     */
    protected $user;

    public function __construct(GameUser $user)
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
