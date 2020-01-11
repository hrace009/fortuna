<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Events\SocialLogin;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Repositories\User\UserRepository;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Contracts\User as SocialUser;

class OAuthController extends Controller
{
    use AuthenticatesUsers;

    /**
     * @var UserRepository
     */
    private $users;

    /**
     * Create a new controller instance.
     */
    public function __construct(UserRepository $user)
    {
        config([
            'services.facebook.redirect' => route('oauth.callback', 'facebook'),
            'services.google.redirect' => route('oauth.callback', 'google'),
        ]);

        $this->users = $user;
    }

    /**
     * Redirect the user to the provider authentication page.
     *
     * @param string $driver
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToProvider($driver)
    {
        return Socialite::driver($driver)->stateless()->redirect();
    }

    /**
     * Obtain the user information from the provider.
     *
     * @param string $driver
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($driver)
    {
        if (! request()->has('code') || request()->has('denied')) {
            return redirect('/');
        }

        $socialUser = Socialite::driver($driver)->stateless()->user();

        $user = $this->users->findProvider($driver, $socialUser->getId());

        if (! $user) {
            $user = $this->createOrAssociateUser($socialUser, $driver);
        }

        event(new SocialLogin($user, $driver));

        return $this->login($user);
    }

    /**
     * Create account for user authenticated via social network.
     * If user with the same email address retrieved from social network
     * exists in our database, just associate it with provided social account.
     *
     * @param SocialUser $socialUser
     * @param $provider
     *
     * @return \Vanguard\User
     */
    private function createOrAssociateUser(SocialUser $socialUser, $provider)
    {
        $user = $this->users->findByEmail($socialUser->getEmail());

        if (! $user) {
            // User with email retrieved from social auth provider does not
            // exist in our database. That means that we have to create new user here
            $user = User::create([
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'status' => 'CONFIRMED',
            ]);

            $user->oauthProviders()->create([
                'provider' => $provider,
                'provider_user_id' => $socialUser->getId(),
                'access_token' => $socialUser->token,
                'refresh_token' => $socialUser->refreshToken,
            ]);
        }

        // Associate social account with user account inside our application
        $this->users->createOrAssociateUser($user->id, $provider, $socialUser);

        return $user;
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

        return redirect('/home')->withCookie(
            cookie('token', $token, 0, null, null, false, false)
        );
    }
}
