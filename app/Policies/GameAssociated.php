<?php

namespace App\Policies;

use App\Game\User as UserGame;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GameAssociated
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the associated game account.
     *
     * @param \App\Models\User   $user
     * @param \App\Ticket $ticket
     *
     * @return mixed
     */
    public function view(User $user, UserGame $game)
    {
        if ($user->id === $game->cid) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update their game account data.
     *
     * @param \App\Models\User     $user
     * @param \App\Models\UserGame $game
     *
     * @return mixed
     */
    public function update(User $user, UserGame $game)
    {
        if ($game->cid === $user->id) {
            return true;
        }

        return false;
    }
}
