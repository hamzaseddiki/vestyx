<?php

namespace Modules\EmailTemplate\Http\Controllers\Landlord;

use App\Facades\GlobalLanguage;
use App\Helpers\FlashMsg;
use App\Helpers\LanguageHelper;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Modules\EmailTemplate\Traits\EmailTemplateHelperTrait;

class EmailTemplateController extends Controller
{
    use EmailTemplateHelperTrait;
    const BASE_PATH = 'emailtemplate::landlord.email-template.';

    public function all(){
        return view(self::BASE_PATH.'all')->with(['all_languages' => GlobalLanguage::all_languages(1)]);
    }

    public function admin_password_reset(){

        return view(self::BASE_PATH.'admin-reset-password')->with(['all_languages' => GlobalLanguage::all_languages(1)]);
    }

    public function user_email_verify(){

        return view(self::BASE_PATH.'user-email-verify')->with(['all_languages' => GlobalLanguage::all_languages(1)]);
    }

    public function admin_email_verify(){

        return view(self::BASE_PATH.'admin-email-verify')->with(['all_languages' => GlobalLanguage::all_languages(1)]);
    }

    public function newsletter_verify(){
        return view(self::BASE_PATH.'newsletter-verify')->with(['all_languages' => GlobalLanguage::all_languages(1)]);
    }
    public function update_admin_password_reset(Request $request){
        $this->save_data('admin_reset_password_',$request);
        return back()->with(FlashMsg::settings_update());
    }


    public function update_user_email_verify(Request $request){

        $this->save_data('user_email_verify_',$request);

        return back()->with(FlashMsg::settings_update());
    }

    public function update_admin_email_verify(Request $request){

        $this->save_data('admin_email_verify_',$request);
        return back()->with(FlashMsg::settings_update());
    }

    public function user_password_reset(){
        return view(self::BASE_PATH.'user-reset-password')->with(['all_languages' => GlobalLanguage::all_languages(1)]);
    }

    public function update_user_password_reset(Request $request){

        foreach (GlobalLanguage::all_languages(1) as $lang){
            $this->validate($request,[
                'user_reset_password_'.$lang->slug.'_subject',
                'user_reset_password_'.$lang->slug.'_message',
            ]);
            $fields_list = [
                'user_reset_password_'.$lang->slug.'_subject',
                'user_reset_password_'.$lang->slug.'_message',
            ];
            foreach ($fields_list as $field){
                update_static_option($field,$request->$field);
            }
        }

        return back()->with(FlashMsg::settings_update());
    }


    public function subscription_order_mail_admin(){

        return view(self::BASE_PATH.'subscription-order-mail-admin')->with(['all_languages' => GlobalLanguage::all_languages(1)]);
    }

    public function update_subscription_order_mail_admin(Request $request){
        $this->save_data('subscription_order_mail_admin_',$request);
        return back()->with(FlashMsg::settings_update());
    }

    public function subscription_order_mail_user(){

        return view(self::BASE_PATH.'subscription-order-mail-user')->with(['all_languages' => GlobalLanguage::all_languages(1)]);
    }

    public function update_subscription_order_mail_user(Request $request){
        $this->save_data('subscription_order_mail_user_',$request);
        return back()->with(FlashMsg::settings_update());
    }

    public function subscription_order_credential_mail_user(){

        return view(self::BASE_PATH.'subscription-order-credential-mail-user')->with(['all_languages' => GlobalLanguage::all_languages(1)]);
    }

    public function update_subscription_order_credential_mail_user(Request $request){
        $this->save_data('subscription_order_credential_mail_user_',$request);
        return back()->with(FlashMsg::settings_update());
    }


    public function subscription_order_trial_mail_user(){

        return view(self::BASE_PATH.'subscription-order-trial-mail-user')->with(['all_languages' => GlobalLanguage::all_languages(1)]);
    }

    public function update_subscription_order_trial_mail_user(Request $request){
        $this->save_data('subscription_order_trial_mail_user_',$request);
        return back()->with(FlashMsg::settings_update());
    }


    public function subscription_order_manual_payment_approved_mail(){
        return view(self::BASE_PATH.'subscription-order-manual-payment-approved-mail')->with(['all_languages' => GlobalLanguage::all_languages(1)]);
    }

    public function update_subscription_order_manual_payment_approved_mail(Request $request){
        $this->save_data('subscription_order_manual_payment_approved_mail_',$request);
        return back()->with(FlashMsg::settings_update());
    }



}
