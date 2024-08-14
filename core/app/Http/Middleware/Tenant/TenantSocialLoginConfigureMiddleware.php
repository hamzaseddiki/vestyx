<?php

namespace App\Http\Middleware\Tenant;

use App\Models\StaticOption;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class TenantSocialLoginConfigureMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (tenant()){
            //todo if it is tenant configure smtp from here

            $smtp_settings_values = StaticOption::select(['option_name','option_value'])->whereIn('option_name',[
                'google_client_id',
                'google_client_secret',
                'facebook_client_id',
                'facebook_client_secret'
            ])->get()->pluck('option_value','option_name')->toArray();
            Config::set([
                'services.facebook.client_id' => $smtp_settings_values['facebook_client_id'] ?? '',
                'services.facebook.client_secret' => $smtp_settings_values['facebook_client_secret'] ?? '',
                'services.facebook.redirect' => url("/facebook/callback"),
                'services.google.client_id' => $smtp_settings_values['google_client_id'] ?? '',
                'services.google.client_secret' => $smtp_settings_values['google_client_secret'] ?? '',
                'services.google.redirect' =>url("/google/callback")
            ]);
        }
        return $next($request);
    }
}
