<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Roles;
use Illuminate\Auth\Access\HandlesAuthorization;

class GameRolePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the roles.
     *
     * @param \App\Models\User  $user
     *
     * @param \App\App\Models\Roles  $roles
     *
     * @return mixed
     */
    public function view(User $user, Roles $role)
    {
        // We check here if the role game account
        // is associated with the Central's account.
        if ($user->id === $role->game->cid) {
            return true;
        }

        return false;
    }
}
