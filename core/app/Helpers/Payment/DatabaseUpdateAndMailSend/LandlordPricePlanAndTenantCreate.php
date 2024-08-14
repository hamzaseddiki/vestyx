<?php

namespace App\Helpers\Payment\DatabaseUpdateAndMailSend;

use App\Events\TenantRegisterEvent;
use App\Mail\BasicDynamicTemplateMail;
use App\Mail\BasicMail;
use App\Mail\PlaceOrder;
use App\Mail\TenantCredentialMail;
use App\Models\PaymentLogHistory;
use App\Models\PaymentLogs;
use App\Models\Tenant;
use App\Models\TenantException;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\EmailTemplate\Traits\EmailTemplate\Landlord\SubscriptionEmailTemplate;


class LandlordPricePlanAndTenantCreate
{
    public static function update_database($order_id, $transaction_id)
    {
        $log = PaymentLogs::find($order_id);
        $status_condition = $log->status == 'trial' ? $log->status : 'complete';

        $log->transaction_id = $transaction_id;
        $log->status = $status_condition;
        $log->payment_status = 'complete';
        $log->updated_at = Carbon::now();
        $log->save();

        self::store_payment_log_history($log->id);
    }

    public static function update_tenant($payment_data)
    {

        $order_id = $payment_data['order_id'] ? $payment_data['order_id'] : $payment_data->id;
        $payment_log = PaymentLogs::find($order_id);
        $tenant = Tenant::find($payment_log->tenant_id);

        $expire_date_con = $payment_log->status == 'trial' ? $payment_log->expire_date : get_plan_left_days($payment_log->package_id, $tenant->expire_date);

        \DB::table('tenants')->where('id', $tenant->id)->update([
            'start_date' => $payment_log->start_date,
            'expire_date' => $expire_date_con,
            'user_id' => $payment_log->user_id,
            'theme_slug' => $payment_log->theme
        ]);
    }

    public static function send_order_mail($order_id)
    {
        $package_details = PaymentLogs::findOrFail($order_id);
        $all_fields = [];
        unset($all_fields['package']);
        $order_mail = get_static_option('order_page_form_mail');
        $all_attachment = [];
        $order_mail = !empty($order_mail) ? $order_mail : get_static_option('site_global_email');

        $lang = get_user_lang();
        $admin_dynamic_mail_sub = get_static_option('subscription_order_mail_admin_'.$lang.'_subject');
        $admin_dynamic_mail_mess = get_static_option('subscription_order_mail_admin_'.$lang.'_message');

        $user_dynamic_mail_sub = get_static_option('subscription_order_mail_user_'.$lang.'_subject');
        $user_dynamic_mail_mess = get_static_option('subscription_order_mail_user_'.$lang.'_message');


            //Admin Mail
            if(!empty($admin_dynamic_mail_sub) && !empty($admin_dynamic_mail_mess)){
                Mail::to($order_mail)->send(new BasicDynamicTemplateMail(SubscriptionEmailTemplate::SubscriptionAdminMail($package_details)));
            }else{
                Mail::to($order_mail)->send(new PlaceOrder($all_fields, $all_attachment, $package_details, "admin", 'regular'));
            }

            //User Mail
            if(!empty($user_dynamic_mail_sub) && !empty($user_dynamic_mail_mess)){
                Mail::to($package_details->email)->send(new BasicDynamicTemplateMail(SubscriptionEmailTemplate::SubscriptionUserMail($package_details)));
            }else{
                Mail::to($package_details->email)->send(new PlaceOrder($all_fields, $all_attachment, $package_details, 'user', 'regular'));
            }

    }


    public static function tenant_create_event_with_credential_mail($order_id,$event=true)
    {
        $log = PaymentLogs::findOrFail($order_id);

        $user = User::where('id', $log->user_id)->first();
        $tenant = Tenant::find($log->tenant_id);

        if (!empty($log) && $log->payment_status == 'complete' && is_null($tenant)) {
                self::createDatabaseUsingEventListener($log,$user,$event);
        } else if (!empty($log) && $log->payment_status == 'complete' && !is_null($tenant) && $log->is_renew == 0) {
                self::createDatabaseUsingEventListener($log,$user,$event);
        }

        return true;
    }

    public static function createDatabaseUsingEventListener($log,$user,$event=true){

        if($event){
            event(new TenantRegisterEvent($user, $log->tenant_id,$log->theme));
        }

        $credential_username = get_static_option_central('landlord_default_tenant_admin_username_set') ?? 'super_admin';
        $raw_pass = get_static_option_central('landlord_default_tenant_admin_password_set') ?? '12345678';
        $credential_email = $user->email;

        $credential_password = !empty(get_static_option_central('tenant_seeding_password_status')) ? \session()->get('random_password_for_tenant') : $raw_pass;

        $lang = get_user_lang();
        $user_dynamic_mail_sub = get_static_option('subscription_order_credential_mail_user_'.$lang.'_subject');
        $user_dynamic_mail_mess = get_static_option('subscription_order_credential_mail_user_'.$lang.'_message');

        try {
            if(!empty($user_dynamic_mail_sub) && !empty($user_dynamic_mail_mess)){
                Mail::to($credential_email)->send(new BasicDynamicTemplateMail(SubscriptionEmailTemplate::SubscriptionCredentialMail($log)));
            }else{
                Mail::to($credential_email)->send(new TenantCredentialMail($credential_username, $credential_password));
            }
        } catch (\Exception $e) {

        }



    }

    public static function store_exception($tenant_id,$issue_type,$description,$domain_create_status)
    {
            TenantException::updateOrCreate([
                'tenant_id' => $tenant_id
            ],[
                'tenant_id' => $tenant_id,
                'issue_type' => $issue_type,
                'description' => $description,
                'domain_create_status' => $domain_create_status,
            ]);
    }

    public static function store_payment_log_history($log_id)
    {
        $payment_log = PaymentLogs::find($log_id);

        if($payment_log->payment_status == 'complete'){

           PaymentLogHistory::create([
                'email' => $payment_log->email,
                'name' => $payment_log->name,
                'package_name' => $payment_log->package_name,
                'package_price' => $payment_log->package_price,
                'package_gateway' => $payment_log->package_gateway,
                'package_id' => $payment_log->package_id,
                'user_id' => $payment_log->user_id,
                'tenant_id' => $payment_log->tenant_id,
                'coupon_id' => $payment_log->coupon_id ,
                'coupon_discount' => $payment_log->coupon_discount,
                'status' => $payment_log->status,
                'payment_status' =>$payment_log->payment_status,
                'is_renew' => $payment_log->is_renew,
                'track' => $payment_log->track,
                'transaction_id' => $payment_log->transaction_id,
                'created_at' => $payment_log->created_at,
                'updated_at' => $payment_log->updated_at,
                'start_date' => $payment_log->start_date,
                'expire_date' => $payment_log->expire_date,
                'theme' => $payment_log->theme,
                'assign_status' => $payment_log->assign_status,
                'manual_payment_attachment' => $payment_log->manual_payment_attachment,
            ]);
        }

    }

}
