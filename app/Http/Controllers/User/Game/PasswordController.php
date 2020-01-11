<?php

namespace App\Http\Controllers\User\Game;

use Illuminate\Http\Response;
use App\Events\GamePasswordChanged;
use App\Http\Controllers\Controller;

class PasswordController extends Controller
{
    public function update($user)
    {
        $data = request()->validate([
            'password' => 'required|confirmed|min:6',
        ]);

        $user = request()->user()->accounts()->where('ID', decodeHashIdentifier($user))->firstOrFail();

        abort_unless(request()->user()->can('update', $user), Response::HTTP_FORBIDDEN);

        $user->update([
            'passwd' => hash_passwd($user->name, $data['password']),
        ]);

        event(new GamePasswordChanged($user));

        return response()->json([
            'success' => true,
        ], Response::HTTP_OK);
    }
}
