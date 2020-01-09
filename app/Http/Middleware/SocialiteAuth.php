<?php

namespace App\Http\Middleware;

use Closure;

class SocialiteAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $provider = $request->route()->parameter('driver');

        if (! in_array($provider, config('auth.social.drivers'))) {
            abort(404);
        }

        if (! config('services.social_auth')) {
            abort(403, 'Social authentication is not enabled.');
        }

        return $next($request);
    }
}
