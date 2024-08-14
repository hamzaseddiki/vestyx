<?php

namespace Modules\EmailTemplate\Traits\EmailTemplate\Tenant;

trait JobEmailTemplate
{
    /**
     * send jobUserMail
     * */
    public function jobUserMail($applicant_details)
    {
        $message = get_static_option('job_user_mail_' . get_user_lang() . '_message');
        $message = $this->parseJobInfo($message, $applicant_details);
        return [
            'subject' => get_static_option('job_user_mail_' . get_user_lang() . '_subject'),
            'message' => $message,
        ];
    }

    /**
     * send jobAdminMail
     * */
    public function jobAdminMail($applicant_details)
    {
        $message = get_static_option('job_admin_mail_' . get_user_lang() . '_message');
        $message = $this->parseJobInfo($message, $applicant_details);
        return [
            'subject' => get_static_option('job_admin_mail_' . get_user_lang() . '_subject'),
            'message' => $message,
        ];
    }

    private function parseJobInfo($message, $applicant_details)
    {
        $message = str_replace(
            [
                '@applicant_id',
                '@applicant_name',
                '@job_title',
                '@job_application_time',
                '@site_title',
            ],
            [
                $applicant_details->id,
                $applicant_details->name,
                optional($applicant_details->job)->title,
                $applicant_details->created_at->format('D , d m y h:i:s'),
                get_static_option('site_' . get_user_lang() . '_title')
            ], $message);
        return $message;
    }

}
