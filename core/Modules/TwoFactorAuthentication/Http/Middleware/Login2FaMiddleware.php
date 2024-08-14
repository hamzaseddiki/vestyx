<?php

namespace Modules\TwoFactorAuthentication\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use Modules\TwoFactorAuthentication\Support;
use PragmaRX\Google2FALaravel\Google2FA;

class Login2FaMiddleware
{

    public function handle(Request $request, Closure $next){

        if ($request->path() == '/logout'){
            return $next($request);
        }

        //todo check user enabled 2fa or not
        if (\Auth::guard('web')->check()){
            $userInfo = \Auth::guard('web')->user();
            if (optional($userInfo->loginSecurity)->google2fa_enable === 1){
                $authenticator = app(Support\Google2FAAuthenticator::class)->boot($request);
                if ($authenticator->isAuthenticated()) {
                    return $next($request);
                }else{
                    return redirect()->route('frontend.verify.2fa.code');
                }
            }

        }


        return $next($request);
    }

}
