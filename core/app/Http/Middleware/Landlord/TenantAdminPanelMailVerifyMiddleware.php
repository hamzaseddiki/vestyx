<?php

namespace App\Http\Middleware\Landlord;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenantAdminPanelMailVerifyMiddleware
{
    public function handle(Request $request, Closure $next)
    {

        $user_info = tenant()->user()->first();
        if(tenant()){
            if (Auth::guard('admin')->check() && $user_info->email_verified == 0) {
                return redirect(route('tenant.admin.email.verify'));
            }
        }


        return $next($request);
    }
}
