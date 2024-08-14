<?php

namespace Modules\TwoFactorAuthentication\Http\Controllers;

use App\Helpers\FlashMsg;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\TwoFactorAuthentication\Entities\LoginSecurity;
use Auth;
use Hash;
use Modules\TwoFactorAuthentication\Support;
use PragmaRX\Google2FALaravel\Google2FA;
use PragmaRX\Google2FALaravel\Support\Authenticator;

class LoginSecurityController extends Controller
{
    public function show2faForm(Request $request){

        $user = Auth::guard('web')->user();
        $google2fa_url = "";
        $secret_key = "";

        if($user->loginSecurity()->exists()){
            $google2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());
            $google2fa_url = \QrCode::size(200)->generate($google2fa->getQRCodeUrl(
                get_static_option("site_en_GB_title"),
                $user->email,
                $user->loginSecurity->google2fa_secret
            ));
            $secret_key = $user->loginSecurity->google2fa_secret;
        }

        return view('twofactorauthentication::frontend.google2FaSettings')->with([
            'user' => $user,
            'secret' => $secret_key,
            'google2fa_url' => $google2fa_url
        ]);
    }

    public function enable2fa(Request $request){

        $user = Auth::guard('web')->user();
        $google2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());

        if ($request->form_type === 'generate_secret_key'){
           LoginSecurity::updateOrCreate([ 'user_id' => $user->id],
                [
                    'google2fa_enable' => 0,
                    'google2fa_secret' => $google2fa->generateSecretKey()
                ]);

            return redirect()->route('landlord.user.enable2fa');
        }


        if ($request->form_type === 'enable_2fa') {
            Validator::make($request->all(),[
                'secret' => 'required'
            ])->validate();
            $secret = $request->input('secret');
            $valid = $google2fa->verifyKey(optional($user->loginSecurity)->google2fa_secret, $secret);
            if($valid){
                $user->loginSecurity->google2fa_enable = 1;
                $user->loginSecurity->save();

                (new Authenticator(request()))->login();

                return redirect()->back()->with(FlashMsg::item_new(__('Enable Google 2Fa Success')));
            }
            return redirect()->back()->with(FlashMsg::item_delete(__('Invalid verification Code, Please try again')));

        }
        abort(404);
    }

    public function disable2fa(Request $request){
        if (!(Hash::check($request->get('current-password'), Auth::user()->password))) {
            return redirect()->back()->with(FlashMsg::item_delete(__('Your password does not matches with your account password. Please try again.')));
        }

         $request->validate([
            'current-password' => 'required',
        ]);
        $user = Auth::user();
        $user->loginSecurity->google2fa_enable = 0;
        $user->loginSecurity->save();
        return redirect()->back()->with(FlashMsg::item_new(__('2FA is now disabled')));

    }


    // one time login
    public function verify_2fa_code(){
        if (!empty(session()->get('google2fa')) && isset(session()->get('google2fa')['auth_passed'])){
            return redirect()->route('landlord.user.home');
        }

        return view('twofactorauthentication::frontend.2fa-verify');
    }

    public function verify_secret_code(Request $request){

        Validator::validate($request->all(),[
           'one_time_password' => 'required|min:6'
        ]);

        $user = Auth::guard('web')->user();
        $google2fa = (new \PragmaRX\Google2FAQRCode\Google2FA());
        $valid = $google2fa->verifyKey(optional($user->loginSecurity)->google2fa_secret, $request->one_time_password);

        if ($valid){
            (new Authenticator(request()))->login();
            return redirect()->route('landlord.user.home');
        }

        return redirect()->back()->with(['msg' => __('security code verify failed, please try again'),'type' => 'danger']);
    }

}
