<?php

namespace App\Http\Controllers\Admin\EmailTemplate;

use App\Helpers\FlashMsg;
use App\Helpers\LanguageHelper;
use App\Http\Controllers\Controller;
use App\Traits\EmailTemplateHelperTrait;
use Illuminate\Http\Request;

class JobApplicantEmailTemplateController extends Controller
{
    use EmailTemplateHelperTrait;
    const BASE_PATH = 'backend.email-template.job.';

    public function job_application_admin(){
        return view(self::BASE_PATH.'admin-mail')->with(['all_languages' => LanguageHelper::all_languages()]);
    }
    public function job_application_user(){
        return view(self::BASE_PATH.'user-mail')->with(['all_languages' => LanguageHelper::all_languages()]);
    }

    public function update_job_application_admin(Request $request){
        $this->save_data('job_admin_mail_',$request);

        return back()->with(FlashMsg::settings_update());
    }
    public function update_job_application_user(Request $request){
        $this->save_data('job_user_mail_',$request);

        return back()->with(FlashMsg::settings_update());
    }

}
