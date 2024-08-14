<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Traits\SeoDataConfig;
use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PragmaRX\Google2FALaravel\Google2FA;
use PragmaRX\Google2FALaravel\Support\Authenticator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers,SeoDataConfig,SEOToolsTrait;

    public function showLoginForm()
    {
        $this->setMetaDataInfo(null,[
            'title' => __('Login'),
            'description' => __('login description')
        ]);
        return view('landlord.frontend.auth.login');
    }

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function credentials(Request $request)
    {
        return  [$this->username() => $request->email , 'password' => $request->password];
    }

    protected function validateLogin(Request $request)
    {

        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ],[
            'email.required'   => __('email or username required'),
            'password.required' => __('password required')
        ]);
    }

    public function username()
    {
        $type = 'username';
        //check is email or user name
        if (filter_var(\request()->email,FILTER_VALIDATE_EMAIL)){
            $type = 'email';
        }
        return $type;
    }


}
