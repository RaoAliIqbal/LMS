<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Agent
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$guard = null)
    {
        if (Auth::guard($guard)->check()) {

            $user = Auth::user();

            if ($user->roles()->first() && $user->roles()->first()->id == 3) {

                return $next($request);
            } else {
                abort(403);
            }

        }
        return redirect(route('login'));
    }
}
