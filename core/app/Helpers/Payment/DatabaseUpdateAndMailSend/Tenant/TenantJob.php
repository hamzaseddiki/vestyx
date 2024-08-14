<?php

namespace App\Helpers\Payment\DatabaseUpdateAndMailSend\Tenant;
use App\Mail\EventMail;
use App\Mail\JobMail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Modules\Event\Entities\Event;
use Modules\Event\Entities\EventPaymentLog;
use Modules\Job\Entities\JobPaymentLog;

class TenantJob
{
    public static function update_database($order_id, $transaction_id)
    {
        $job_log = JobPaymentLog::findOrFail($order_id);
        $job_log->transaction_id = $transaction_id;
        $job_log->status = 1;
        $job_log->updated_at = Carbon::now();
        $job_log->save();
    }

    public static function send_job_mail($order_id)
    {
        $package_details = JobPaymentLog::findOrFail($order_id);
        $tenant_admin_mail_address = get_static_option('tenant_site_global_email');

        $customer_subject = __('Your job application success for').' '.get_static_option('site_'.get_user_lang().'_title');
        $admin_subject_with_payment = __('You have a new job application payment from').' '.get_static_option('site_'.get_user_lang().'_title');
        $admin_subject_without_payment = __('You have a new job application from').' '.get_static_option('site_'.get_user_lang().'_title');
        $admin_subject = is_null($package_details->payment_gateway) ? $admin_subject_without_payment : $admin_subject_with_payment;

        try {
            Mail::to($tenant_admin_mail_address)->send(new JobMail($package_details, $admin_subject,"admin"));
            Mail::to($package_details->email)->send(new JobMail( $package_details, $customer_subject,'user'));

        } catch (\Exception $e) {
            return redirect()->back()->with(['type' => 'danger', 'msg' => $e->getMessage()]);
        }
    }

}
