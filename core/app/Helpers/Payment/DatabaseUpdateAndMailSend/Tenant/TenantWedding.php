<?php

namespace App\Helpers\Payment\DatabaseUpdateAndMailSend\Tenant;
use App\Mail\WeddingMail;
use App\Models\WeddingPaymentLog;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class TenantWedding
{
    public static function update_database($order_id, $transaction_id)
    {
        WeddingPaymentLog::where('id', $order_id)->update([
            'transaction_id' => $transaction_id,
            'status' => 'complete',
            'payment_status' => 'complete',
            'updated_at' => Carbon::now()
        ]);
    }

    public static function send_wedding_order_mail($order_id)
    {
        $package_details = WeddingPaymentLog::findOrFail($order_id);
        $tenant_admin_mail_address = get_static_option('tenant_site_global_email');

        $customer_subject = __('Your wedding plan payment success for').' '.get_static_option('site_'.get_user_lang().'_title');
        $admin_subject = __('You have a new wedding plan payment from').' '.get_static_option('site_'.get_user_lang().'_title');

        try {
            Mail::to($tenant_admin_mail_address)->send(new WeddingMail($package_details, $admin_subject,"admin"));
            Mail::to($package_details->email)->send(new WeddingMail( $package_details, $customer_subject,'user'));

        } catch (\Exception $e) {
            return redirect()->back()->with(['type' => 'danger', 'msg' => $e->getMessage()]);
        }
    }

}
