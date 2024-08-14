<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{

    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if ( (\request()->path() === 'admin' || \request()->is('admin-home/*' ))) {

                if (Auth::guard('admin')->check()){
                    return redirect('/admin-home');
                }
                return $next($request);
            }

            if ( (\request()->path() === 'login')) {

                if (Auth::guard('web')->check()){
                    return redirect()->route('landlord.homepage');
                }
                return $next($request);
            }
             return $next($request);
        }

        return $next($request);
    }
}
