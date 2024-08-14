<?php

namespace App\Http\Controllers\Admin\EmailTemplate;

use App\Helpers\FlashMsg;
use App\Helpers\LanguageHelper;
use App\Http\Controllers\Controller;
use App\Traits\EmailTemplateHelperTrait;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    use EmailTemplateHelperTrait;
    const BASE_PATH = 'backend.email-template.';

    public function all(){
        return view(self::BASE_PATH.'all');
    }

    public function admin_password_reset(){
        return view(self::BASE_PATH.'admin-reset-password')->with(['all_languages' => LanguageHelper::all_languages()]);
    }

    public function Auser_email_verify(){

        return view(self::BASE_PATH.'user-email-verify')->with(['all_languages' => LanguageHelper::all_languages()]);
    }

    public function admin_email_verify(){

        return view(self::BASE_PATH.'admin-email-verify')->with(['all_languages' => LanguageHelper::all_languages()]);
    }

    public function newsletter_verify(){
        return view(self::BASE_PATH.'newsletter-verify')->with(['all_languages' => LanguageHelper::all_languages()]);
    }
    public function update_admin_password_reset(Request $request){
        $this->save_data('admin_reset_password_',$request);
        return back()->with(FlashMsg::settings_update());
    }
    

    public function update_newsletter_verify(Request $request){
        $this->save_data('newsletter_verify_',$request);

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
        return view(self::BASE_PATH.'user-reset-password')->with(['all_languages' => LanguageHelper::all_languages()]);
    }

    public function update_user_password_reset(Request $request){

        foreach (LanguageHelper::all_languages() as $lang){
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



}
