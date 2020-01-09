<?php

namespace App\Http\Middleware;

use Closure;

class UserForbidden
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
        if ($request->user() && ($request->user()->status === 'FORBIDDEN')) {
            return response()->json(
                [
                    'error' => 'Esta ação não é permitida pois sua conta foi bloqueada. Dúvidas, entre em contato.',
                ],
            400);
        }

        return $next($request);
    }
}
