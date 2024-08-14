<?php

namespace App\Http\Middleware\Tenant;

use Closure;
use Illuminate\Http\Request;

class TenantAccountStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $tenant_log = tenant()?->payment_log;
            if ($tenant_log?->payment_status != 'pending' && $tenant_log?->payment_status != 'cancel')
            {
                if ($tenant_log?->status != 'pending' && $tenant_log?->status != 'cancel')
                {
                    return $next($request);
                }
            } else if ($tenant_log?->status == 'trial'){
                return $next($request);
            }

        return redirect()->route('tenant.admin.restricted');
    }
}
