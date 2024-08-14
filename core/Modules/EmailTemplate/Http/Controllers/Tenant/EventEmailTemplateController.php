<?php

namespace App\Http\Controllers\Admin\EmailTemplate;

use App\Helpers\FlashMsg;
use App\Helpers\LanguageHelper;
use App\Http\Controllers\Controller;
use App\Traits\EmailTemplateHelperTrait;
use Illuminate\Http\Request;

class EventEmailTemplateController extends Controller
{
    use EmailTemplateHelperTrait;
    const BASE_PATH = 'backend.email-template.event.';

    public function event_attendance_mail_admin(){
        return view(self::BASE_PATH.'booking-mail-admin')->with(['all_languages' => LanguageHelper::all_languages()]);
    }

    public function event_attendance_mail_user(){
        return view(self::BASE_PATH.'booking-mail-user')->with(['all_languages' => LanguageHelper::all_languages()]);
    }

    public function event_attendance_mail_payment_accept(){
        return view(self::BASE_PATH.'booking-payment-accept')->with(['all_languages' => LanguageHelper::all_languages()]);
    }
    public function event_attendance_mail_reminder_mail(){
        return view(self::BASE_PATH.'booking-reminder-mail')->with(['all_languages' => LanguageHelper::all_languages()]);
    }

    public function update_event_attendance_mail_reminder_mail(Request $request){
        $this->save_data('event_booking_reminder_',$request);
        return back()->with(FlashMsg::settings_update());
    }

    public function update_event_attendance_mail_payment_accept(Request $request){
        $this->save_data('event_booking_payment_accept_',$request);
        return back()->with(FlashMsg::settings_update());
    }

    public function update_event_attendance_mail_admin(Request $request){
        $this->save_data('event_booking_admin_mail_',$request);

        return back()->with(FlashMsg::settings_update());
    }
    public function update_event_attendance_mail_user(Request $request){
        $this->save_data('event_booking_user_mail_',$request);

        return back()->with(FlashMsg::settings_update());
    }
}
