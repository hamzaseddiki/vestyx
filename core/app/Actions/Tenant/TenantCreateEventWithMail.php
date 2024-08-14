<?php

namespace App\Actions\Tenant;

use App\Events\TenantRegisterEvent;
use App\Mail\BasicDynamicTemplateMail;
use App\Mail\TenantCredentialMail;
use App\Models\PaymentLogs;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Modules\EmailTemplate\Traits\EmailTemplate\Landlord\SubscriptionEmailTemplate;

class TenantCreateEventWithMail
{
    public static function tenant_create_event_with_credential_mail($user, $subdomain)
    {
        event(new TenantRegisterEvent($user, $subdomain));

        try {
            $raw_pass = get_static_option_central('landlord_default_tenant_admin_password_set') ?? '12345678';
            $credential_password = !empty(get_static_option_central('tenant_seeding_password_status')) ? \session()->get('random_password_for_tenant') : $raw_pass;
            $credential_email = $user->email;
            $credential_username = get_static_option_central('landlord_default_tenant_admin_username_set') ?? 'super_admin';

            $lang = get_user_lang();
            $user_dynamic_mail_sub = get_static_option('subscription_order_trial_mail_user_'.$lang.'_subject');
            $user_dynamic_mail_mess = get_static_option('subscription_order_trial_mail_user_'.$lang.'_message');

            $log = PaymentLogs::where('tenant_id',$subdomain)->first() ?? [];

            //User trial Credential Mail
            if(!empty($user_dynamic_mail_sub) && !empty($user_dynamic_mail_mess)){
                Mail::to($credential_email)->send(new BasicDynamicTemplateMail(SubscriptionEmailTemplate::SubscriptionTrialMail($log)));
            }else{
                Mail::to($credential_email)->send(new TenantCredentialMail($credential_username, $credential_password));
            }

        } catch (\Exception $e) {}
        return true;
    }
}
