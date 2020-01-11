<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ActivationController extends Controller
{
    /**
     * Confirm the user's email address.
     *
     * @param string $token
     *
     * @return void
     */
    public function confirmEmail(string $token, Request $request)
    {
        if ($user = User::ByConfirmationToken($token)->first()) {
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
