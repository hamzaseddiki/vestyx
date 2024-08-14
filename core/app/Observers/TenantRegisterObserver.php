<?php

namespace App\Observers;

use App\Helpers\EmailHelpers\MarkupGenerator;
use App\Helpers\EmailHelpers\VerifyUserMailSend;
use App\Mail\BasicMail;
use App\Models\CustomDomain;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class TenantRegisterObserver
{

    public function created(User $user)
    {
        /* send mail to admin about new user registration */
        $this->mailToAdminAboutUserRegister($user);
        /* send email verify mail to user */
        VerifyUserMailSend::sendMail($user);
        CustomDomain::create(['user_id' => $user->id]);
    }

    private function mailToAdminAboutUserRegister(User $user)
    {

        $msg = MarkupGenerator::paragraph(__('Hello'));
        $msg.= MarkupGenerator::paragraph(sprintf(__('you have a user registration at %s'),site_title()));
        $subject = sprintf(__('new user registration at %s'),site_title());
        try {
            Mail::to(site_global_email())->send(new BasicMail($msg,$subject));
        }catch (\Exception $e){
            //handle exception
        }

    }

}
