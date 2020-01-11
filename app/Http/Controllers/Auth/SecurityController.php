<?php

namespace App\Http\Controllers\Auth;

use Cache;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\TwoFactorRequest;
use App\Http\Requests\TwoFactorBackupRequest;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class SecurityController extends Controller
{
    use AuthenticatesUsers;

    public function security(TwoFactorRequest $request)
    {
        // Get user
        $userId = decrypt($request->twofactor_token);
        $key = $userId.':'.$request->one_time_password;

        //use cache to store token to blacklist
        Cache::add($key, true, 4);

        //login and redirect user
        $user = User::find($userId);

        return $this->login($user);
    }

    public function backup(TwoFactorBackupRequest $request)
    {
        $userId = decrypt($request->twofactor_token);

        //login and redirect user
        $user = User::find($userId);

        return $this->login($user);
    }

    /**
     * Log provided user in and redirect him to intended page.
     *
     * @param $user
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    private function login($user)
    {
        $token = $this->guard()->login($user);
        $this->guard()->setToken($token);
        $expiration = $this->guard()->getPayload()->get('exp');

        return [
            'token'      => (string) $this->guard()->getToken(),
            'token_type' => 'bearer',
            'expires_in' => $expiration - time(),
        ];
    }
}
