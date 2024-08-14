<?php

namespace App\Helpers\Payment\DatabaseUpdateAndMailSend\Tenant;
use App\Mail\AppointmentMail;
use App\Mail\EventMail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Modules\Appointment\Entities\AppointmentPaymentLog;
use Modules\Event\Entities\Event;
use Modules\Event\Entities\EventPaymentLog;

class TenantAppointment
{
    public static function update_database($order_id, $transaction_id)
    {
        $event_log = AppointmentPaymentLog::find($order_id);
        $event_log->transaction_id = $transaction_id ?? null;
        $event_log->status = 'complete';
        $event_log->payment_status = 'complete';
        $event_log->updated_at = Carbon::now();
        $event_log->save();
    }

    public static function send_event_mail($order_id)
    {
        $package_details = AppointmentPaymentLog::findOrFail($order_id);
        $tenant_admin_mail_address = get_static_option('tenant_site_global_email');

        $customer_subject = __('Your booking payment success for').' '.get_static_option('site_'.get_user_lang().'_title');
        $admin_subject = __('You have a new appointment booking payment from').' '.get_static_option('site_'.get_user_lang().'_title');

        try {
            Mail::to($tenant_admin_mail_address)->send(new AppointmentMail($package_details, $admin_subject,"admin"));
            Mail::to($package_details->email)->send(new AppointmentMail( $package_details, $customer_subject,'user'));

        } catch (\Exception $e) {
            return redirect()->back()->with(['type' => 'danger', 'msg' => $e->getMessage()]);
        }
    }

}
