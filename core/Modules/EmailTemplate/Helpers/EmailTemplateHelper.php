<?php

namespace Modules\EmailTemplate\Helpers;
use Modules\EmailTemplate\Traits\EmailTemplateHelperTrait;

class EmailTemplateHelper
{

    public static function userVerifyMail($user)
    {
        $message = get_static_option('user_email_verify_' . get_user_lang() . '_message');
        $message = self::parseUserInfo($message, $user);
        return [
            'subject' => get_static_option('user_email_verify_' . get_user_lang() . '_subject'),
            'message' => $message,
        ];
    }

    public static function adminVerifyMail($user)
    {
        $admin_session_lang = session()->get('register_user_lang') ?? get_user_lang();

        $message = get_static_option('admin_email_verify_' . $admin_session_lang . '_message');
        $message = self::parseUserInfo($message, $user);

        return [
            'subject' => get_static_option('admin_email_verify_' . $admin_session_lang . '_subject'),
            'message' => $message,
        ];
    }

    private static function parseUserInfo(string $message, $user)
    {
        $admin_session_lang = session()->get('register_user_lang') ?? get_user_lang();
        $message = str_replace(
            [
                '@name',
                '@username',
                '@verify_code',
                '@site_title',
            ],
            [
                $user->name,
                $user->username,
                '<span class="verify-code">' . $user->email_verify_token . '</span>',
                get_static_option('site_' . $admin_session_lang. '_title')
            ], $message);

        return $message;
    }



}
