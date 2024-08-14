<?php

namespace App\Helpers\Payment\DatabaseUpdateAndMailSend\Tenant;
use App\Mail\EventMail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Modules\Event\Entities\Event;
use Modules\Event\Entities\EventPaymentLog;

class TenantEvent
{
    public static function update_database($order_id, $transaction_id)
    {
        $event_log = EventPaymentLog::find($order_id);
        $event_log->transaction_id = $transaction_id;
        $event_log->status = 1;
        $event_log->updated_at = Carbon::now();
        $event_log->save();

        if(!empty($event_log)){
            $event = Event::find($event_log->event_id);
            $event->available_ticket = ($event->total_ticket - $event_log->ticket_qty);
            $event->save();
         }

    }

    public static function send_event_mail($order_id)
    {
        $package_details = EventPaymentLog::findOrFail($order_id);
        $tenant_admin_mail_address = get_static_option('tenant_site_global_email');

        $customer_subject = __('Your event attendance payment success for').' '.get_static_option('site_'.get_user_lang().'_title');
        $admin_subject = __('You have a new event attendance payment from').' '.get_static_option('site_'.get_user_lang().'_title');

        try {
            Mail::to($tenant_admin_mail_address)->send(new EventMail($package_details, $admin_subject,"admin"));
            Mail::to($package_details->email)->send(new EventMail( $package_details, $customer_subject,'user'));

        } catch (\Exception $e) {
            return redirect()->back()->with(['type' => 'danger', 'msg' => $e->getMessage()]);
        }
    }

}
