<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            $user = Auth::guard($guard)->user();
            if ($user && $user->hasRole('Admin')) {
                return redirect()->route('admin_dashboard');
            }
            if ($user && $user->hasRole('User')) {
                return redirect()->route('client_profile');
            }
            return redirect(RouteServiceProvider::HOME);
        }

        return $next($request);
    }
}
