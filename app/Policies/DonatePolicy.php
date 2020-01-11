<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Payments;
use App\Support\Enums\PaymentStatus;
use Illuminate\Auth\Access\HandlesAuthorization;

class DonatePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the payments.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Payments  $payments
     * @return mixed
     */
    public function view(User $user, Payments $payments)
    {
        if ($user->id === $payments->user_id) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can create payments.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->orders()->whereTransactionStatus(PaymentStatus::STALLED)->first()) {
            return false;
        }

        if ($user->status === 'FORBIDDEN_TRANSACTION') {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can delete the donation.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Payments  $payments
     * @return mixed
     */
    public function delete(User $user, Payments $payments)
    {
        if ($user->id === $payments->user_id) {
            return true;
        }

        return false;
    }
}
