<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Roles;
use Illuminate\Auth\Access\HandlesAuthorization;

class GameRolesPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the app roles.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Roles  $Roles
     * @return mixed
     */
    public function view(User $user, Roles $roles)
    {
        foreach ($roles as $role) {
            if ($role->game->cid !== $user->id) {
                return false;
            }

            return true;
        }
    }
}
