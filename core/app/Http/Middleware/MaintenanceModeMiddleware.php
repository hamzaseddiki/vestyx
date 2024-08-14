<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaintenanceModeMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!empty(get_static_option('maintenance_mode')) && !Auth::guard('admin')->check()) {
            return response()->view('landlord.frontend.maintain');
        }
        return $next($request);
    }
}
