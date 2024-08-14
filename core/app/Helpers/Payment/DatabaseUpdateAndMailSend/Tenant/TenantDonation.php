<?php

namespace App\Helpers\Payment\DatabaseUpdateAndMailSend\Tenant;
use App\Mail\DonationMail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Modules\Donation\Entities\Donation;
use Modules\Donation\Entities\DonationPaymentLog;

class TenantDonation
{
    public static function update_database($order_id, $transaction_id)
    {
        DonationPaymentLog::where('id', $order_id)->update([
            'transaction_id' => $transaction_id,
            'status' => 1,
            'updated_at' => Carbon::now()
        ]);

        $donation_log = DonationPaymentLog::find($order_id);
        $cause = Donation::where('id',$donation_log->donation_id)->first();
        $cause->raised = ($cause->raised + $donation_log->amount);
        $cause->save();
    }

    public static function send_donation_mail($order_id)
    {
        $package_details = DonationPaymentLog::findOrFail($order_id);
        $tenant_admin_mail_address = get_static_option('donation_alert_receiving_mail') ?? get_static_option('tenant_site_global_email');

        $customer_subject = __('Your donation payment success for').' '.get_static_option('site_'.get_user_lang().'_title');
        $admin_subject = __('You have a new donation payment from').' '.get_static_option('site_'.get_user_lang().'_title');

        try {
            Mail::to($tenant_admin_mail_address)->send(new DonationMail($package_details, $admin_subject,"admin"));
            Mail::to($package_details->email)->send(new DonationMail( $package_details, $customer_subject,'user'));

        } catch (\Exception $e) {
            return redirect()->back()->with(['type' => 'danger', 'msg' => $e->getMessage()]);
        }
    }

}
