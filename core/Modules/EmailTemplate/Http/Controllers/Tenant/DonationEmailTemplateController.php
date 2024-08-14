<?php

namespace App\Http\Controllers\Admin\EmailTemplate;

use App\Helpers\FlashMsg;
use App\Helpers\LanguageHelper;
use App\Http\Controllers\Controller;
use App\Traits\EmailTemplateHelperTrait;
use Illuminate\Http\Request;

class DonationEmailTemplateController extends Controller
{
    use EmailTemplateHelperTrait;
    const BASE_PATH = 'backend.email-template.donation.';

    public function donation_mail_reminder_mail(){
        return view(self::BASE_PATH.'reminder-mail')->with(['all_languages' => LanguageHelper::all_languages()]);
    }

    public function update_donation_mail_reminder_mail(Request $request){
        $this->save_data('donation_payment_reminder_mail_',$request);
        return back()->with(FlashMsg::settings_update());
    }
    public function donation_mail_payment_accept(){
        return view(self::BASE_PATH.'payment-accept-mail')->with(['all_languages' => LanguageHelper::all_languages()]);
    }

    public function update_donation_mail_payment_accept(Request $request){
        $this->save_data('donation_payment_accept_mail_',$request);
        return back()->with(FlashMsg::settings_update());
    }

    public function donation_mail_admin(){
        return view(self::BASE_PATH.'admin-mail')->with(['all_languages' => LanguageHelper::all_languages()]);
    }

    public function update_donation_mail_admin(Request $request){
        $this->save_data('donation_admin_mail_',$request);
        return back()->with(FlashMsg::settings_update());
    }

    public function donation_mail_user(){
        return view(self::BASE_PATH.'user-mail')->with(['all_languages' => LanguageHelper::all_languages()]);
    }

    public function update_donation_mail_user(Request $request){
        $this->save_data('donation_user_mail_',$request);
        return back()->with(FlashMsg::settings_update());
    }

}
