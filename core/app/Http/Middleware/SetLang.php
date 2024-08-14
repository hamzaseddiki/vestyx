<?php

namespace App\Http\Middleware;

use App\Models\User;
use Carbon\Carbon;
use Closure;

class SetLang
{

    public function handle($request, Closure $next)
    {
        $defaultLang =  \App\Models\Language::where('default',1)->first();

        if (session()->has('lang')) {
            $current_lang = \App\Models\Language::where('slug',session()->get('lang'))->first();
            if (!empty($current_lang)){
                Carbon::setLocale($current_lang?->slug);
                app()->setLocale($current_lang?->slug);
            }else {
                session()->forget('lang');
            }
        }else{
            app()->setLocale($defaultLang?->slug ?? 'en');
            Carbon::setLocale($defaultLang?->slug ?? 'en');
        }
        return $next($request);
    }
}
