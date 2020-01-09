<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;

class ActivationController extends Controller
{
    protected $users;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UserRepository $user)
    {
        $this->users = $user;
    }

    /**
     * Confirm the user's email address.
     *
     * @param string $token
     *
     * @return void
     */
    public function confirmEmail(string $token, Request $request)
    {
        if ($user = $this->users->findByConfirmationToken($token)) {
            if ($user->isConfirmed()) {
                return response()->json(['type' => 'danger', 'text' => __('app.errors.account_already_activated')], 422);
            }

            $user->markAsConfirmed();

            activity()->causedBy($user)->log('Cadastro confirmado.');

            return response()->json(['type' => 'success', 'text' => 'Conta confirmada! Você já pode efetuar o login.']);
        }

        return response()->json(['type' => 'danger', 'text' => __('app.errors.invalid_token')], 400);
    }
}
