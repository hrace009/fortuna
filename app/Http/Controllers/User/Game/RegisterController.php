<?php

namespace App\Http\Controllers\User\Game;

use App\Game\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Events\RegisteredGame;
use App\Http\Controllers\Controller;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('user.forbidden');
    }

    public function store()
    {
        request()->validate($this->validateData());

        $user = User::create([
            'ID'       => User::count() > 0 ? User::orderBy('ID', 'desc')->first()->ID + 16 : 32,
            'creatime' => now(),
            'truename' => request()->truename ?? request()->user()->name,
            'name'     => strtolower(request()->name),
            'passwd'   => hash_passwd(strtolower(request()->name), request()->password),
            'email'    => request('email') ?? request()->user()->email,
            'cid'      => (string) request()->user()->id,
        ]);

        event(new RegisteredGame($user));

        return response()->json(['success' => true], Response::HTTP_OK);
    }

    /**
     * @return array
     */
    protected function validateData(): array
    {
        $rules = [
            'name' => 'required|string|min:4|max:32|unique:pw.users',
            'password' => 'required|string|min:6|confirmed',
        ];

        if (app()->isProduction()) {
            return array_merge($rules, [
                'g_recaptcha_response' => 'required|recaptcha',
            ]);
        }

        return $rules;
    }
}
