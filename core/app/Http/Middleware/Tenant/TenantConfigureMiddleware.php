<?php

namespace App\Http\Middleware\Tenant;

use App\Models\StaticOption;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class TenantConfigureMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (tenant()){
            //todo if it is tenant configure smtp from here

            $smtp_settings_values = StaticOption::select(['option_name','option_value'])->whereIn('option_name',[
                'site_smtp_driver',
                'site_smtp_host',
                'site_smtp_port',
                'site_smtp_username',
                'site_smtp_password',
                'site_smtp_encryption',
                'site_global_email'
            ])->get()->pluck('option_value','option_name')->toArray();

            Config::set('mail.mailers', $smtp_settings_values['site_smtp_driver'] ?? Config::get('mail.mailers'));
            $mailers = !empty($smtp_settings_values) ? $smtp_settings_values['site_smtp_driver'] : (get_static_option_central('site_smtp_driver') ?? 'smtp');

            Config::set([
                "mail.mailers.{$mailers}.transport" => $smtp_settings_values['site_smtp_driver'] ?? Config::get('mail.mailers.smtp.transport'),
                "mail.mailers.{$mailers}.host" => $smtp_settings_values['site_smtp_host'] ?? Config::get('mail.mailers.smtp.host'),
                "mail.mailers.{$mailers}.port" => $smtp_settings_values['site_smtp_port'] ?? Config::get('mail.mailers.smtp.port'),
                "mail.mailers.{$mailers}.username" => $smtp_settings_values['site_smtp_username'] ?? Config::get('mail.mailers.smtp.username'),
                "mail.mailers.{$mailers}.password" => $smtp_settings_values['site_smtp_password'] ?? Config::get('mail.mailers.smtp.password'),
                "mail.mailers.{$mailers}.encryption" => $smtp_settings_values['site_smtp_encryption'] ?? Config::get('mail.mailers.smtp.encryption'),
                "mail.from.address" => $smtp_settings_values['site_global_email'] ?? Config::get('mail.from.address')
            ]);

            Config::set('app.timezone',get_static_option_central('timezone'));
        }
        return $next($request);
    }
}


