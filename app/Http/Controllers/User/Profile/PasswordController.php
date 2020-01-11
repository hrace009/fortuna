<?php

namespace App\Http\Controllers\User\Profile;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Events\PasswordChanged as EventPasswordChanged;

class PasswordController extends Controller
{
    /**
     * Update the user's password.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'current_password' => 'required|min:6',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = $request->user();

        if (! Hash::check($request->current_password, $user->password)) {
            return response()->json(
                ['current_password' => ['A senha que você forneceu está incorreta.']],
                422
            );
        }

        event(new EventPasswordChanged($user));

        $user->update([
            'password' => bcrypt($request->password),
        ]);
    }
}
