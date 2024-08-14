<?php

namespace App\Helpers\EmailHelpers;

use App\Mail\BasicMail;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class VerifyUserMailSend
{
    public static function sendMail(User $user){

        $token = Str::random(8);
        User::find($user->id)->update(['email_verify_token' => $token ]);
        $msg = MarkupGenerator::paragraph(__('Hello'));
        $msg .= MarkupGenerator::paragraph(__('Here is your verification code'));
        $msg .= MarkupGenerator::code($token);
        $subject = sprintf(__('Verify your email address at %s'),site_title());

        try {
            Mail::to($user->email)->send(new BasicMail($msg,$subject));
        }catch (\Exception $e){
            return redirect()->back()->with(['msg'=> $e->getMessage(), 'type'=> 'danger']);
        }
    }

    public static function sendMail_tenant_admin(Admin $user){

        $token = Str::random(8);
        $user_info = tenant()->user()->first();
        $user_info->email_verify_token = $token;
        $user_info->save();

        $msg = MarkupGenerator::paragraph(__('Hello'));
        $msg .= MarkupGenerator::paragraph(__('Here is your verification code'));
        $msg .= MarkupGenerator::code($token);
        $subject = sprintf(__('Verify your email address at %s'),site_title());

        try {
            Mail::to($user->email)->send(new BasicMail($msg,$subject));
        }catch (\Exception $e){
            return redirect()->back()->with(['msg'=> $e->getMessage(), 'type'=> 'danger']);
        }
    }
}
