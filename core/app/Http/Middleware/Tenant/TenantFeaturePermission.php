<?php

namespace App\Http\Middleware\Tenant;

use Closure;
use Illuminate\Http\Request;

class TenantFeaturePermission
{
    public function handle(Request $request, Closure $next)
    {
        $routeName = \Route::currentRouteName();
        $routeArr = explode('.',$routeName);
        $arrKey = "";
        if (count($routeArr) > 4)
        {
            $arrKey = count($routeArr)-2; // if inventory
        } elseif(count($routeArr) == 4) {
            $arrKey = count($routeArr)-2; // if campaign
        }

        $check = array_key_exists($arrKey, $routeArr);

        if ($check)
        {
            $name = $routeArr[$arrKey];
        }

        $current_tenant_payment_data = tenant()->payment_log ?? [];

        if (!empty($current_tenant_payment_data) && $check)
        {
            $package = $current_tenant_payment_data->package;

            if (!empty($package))
            {
                $features = $package->plan_features->pluck('feature_name')->toArray();

                if (in_array($name, (array)$features))
                {
                    return $next($request);
                }
            }
        }

        return redirect(url('admin'));
    }
}
