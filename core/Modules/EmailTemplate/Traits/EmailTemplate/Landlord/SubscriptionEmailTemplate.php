<?php

namespace Modules\EmailTemplate\Traits\EmailTemplate\Landlord;

use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

trait SubscriptionEmailTemplate
{

    private static function lang()
    {
        return get_user_lang();
    }
    public static function SubscriptionPaymentAcceptMail($subscription_log_details)
    {
        $message = get_static_option('subscription_order_manual_payment_approved_mail_' .self::lang(). '_message');
        $message = self::parseSubscriptionManualPaymentInfo($message, $subscription_log_details);
        return [
            'subject' => get_static_option('subscription_order_manual_payment_approved_mail_' . self::lang() . '_subject'),
            'message' => $message,
            'type' => 'manual_payment_approved',
        ];
    }


    public static function SubscriptionUserMail($subscription_log_details)
    {

        $message = get_static_option('subscription_order_mail_user_' . self::lang() . '_message');

        $message = self::parseSubscriptionInfo($message, $subscription_log_details,'user');
        return [
            'subject' => get_static_option('subscription_order_mail_user_' . self::lang() . '_subject'),
            'message' => $message,
            'type' => 'user_subscription',
            'logs' => $subscription_log_details
        ];
    }


    public static function SubscriptionAdminMail($subscription_details)
    {
        $message = get_static_option('subscription_order_mail_admin_' . self::lang(). '_message');
        $message = self::parseSubscriptionInfo($message, $subscription_details,'admin');
        return [
            'subject' => get_static_option('subscription_order_mail_admin_' . self::lang() . '_subject'),
            'message' => $message,
            'type' => 'admin_subscription',
            'logs' => $subscription_details
        ];
    }


    public static function SubscriptionCredentialMail($subscription_log_details)
    {
        $message = get_static_option('subscription_order_credential_mail_user_' .self::lang(). '_message');
        $message = self::parseSubscriptionCredentialInfo($message, $subscription_log_details);
        return [
            'subject' => get_static_option('subscription_order_credential_mail_user_' . self::lang(). '_subject'),
            'message' => $message,
            'type' => 'user_credential',
        ];
    }

    public static function SubscriptionTrialMail($subscription_log_details)
    {
        $message = get_static_option('subscription_order_trial_mail_user_' .self::lang(). '_message');
        $message = self::parseSubscriptionTrialInfo($message, $subscription_log_details);
        return [
            'subject' => get_static_option('subscription_order_trial_mail_user_' . self::lang(). '_subject'),
            'message' => $message,
            'type' => 'trial',
        ];
    }

    private static function parseSubscriptionCredentialInfo($message, $subscription_details)
    {
        $credential_username = get_static_option_central('landlord_default_tenant_admin_username_set') ?? 'super_admin';
        $raw_pass = get_static_option_central('landlord_default_tenant_admin_password_set') ?? '12345678';
        $password_condition = !empty(get_static_option_central('tenant_seeding_password_status')) ? \session()->get('random_password_for_tenant') : $raw_pass;

        $message = str_replace(
            [
                '@name',
                '@demo_username',
                '@demo_password',
                '@site_title',
            ],
            [
                $subscription_details->name,
                $credential_username,
                $password_condition,
                get_static_option('site_' . self::lang(). '_title')
            ], $message);

        return $message;
    }


    private static function parseSubscriptionTrialInfo($message, $subscription_details)
    {
        $credential_username = get_static_option_central('landlord_default_tenant_admin_username_set') ?? 'super_admin';
        $raw_pass = get_static_option_central('landlord_default_tenant_admin_password_set') ?? '12345678';
        $password_condition = !empty(get_static_option_central('tenant_seeding_password_status')) ? \session()->get('random_password_for_tenant') : $raw_pass;
        $website_link = tenant_url_with_protocol($subscription_details->tenant_id) . '.'. env('CENTRAL_DOMAIN');
        $admin_panel_link = tenant_url_with_protocol($subscription_details->tenant_id) . '.'. env('CENTRAL_DOMAIN')  .'/admin';

        $message = str_replace(
            [
                '@name',
                '@domain',
                '@demo_username',
                '@demo_password',
                '@trial_start_date',
                '@trial_expire_date',
                '@website_link',
                '@admin_panel_link',
                '@site_title',
            ],
            [
                $subscription_details->name,
                $subscription_details->tenant_id,
                $credential_username,
                $password_condition,
                $subscription_details->start_date,
                Carbon::parse($subscription_details->expire_date)->format('d-m-Y h:i:s'),
                ' <a href="'.$website_link.'">'.__('Visit Website').'</a>',
                ' <a href="'.$admin_panel_link.'">'.__('Visit Admin Panel').'</a>',
                get_static_option('site_' . self::lang(). '_title')
            ], $message);

        return $message;
    }

    private static function parseSubscriptionManualPaymentInfo($message, $subscription_details)
    {
        $message = str_replace(
            [
                '@name',
                '@domain',
                '@order_id',
                '@amount',
                '@package_name',
                '@site_title',
            ],
            [
                $subscription_details->name,
                $subscription_details->tenant_id,
                $subscription_details->id,
                amount_with_currency_symbol($subscription_details->package_price),
                $subscription_details->package_name,
                get_static_option('site_' . self::lang() . '_title')
            ], $message);

        return $message;
    }

    private static function parseSubscriptionInfo($message, $subscription_details, $type)
    {
        $message = str_replace(
            [
                '@user_name',
                '@user_email',
                '@order_id',
                '@order_amount',
                '@package_name',
                '@payment_gateway',
                '@payment_status',
                '@order_date',
                '@domain',
                '@subscription_heading_info',
                '@subscription_details_info',
                '@start_date',
                '@expire_date',
                '@user_dashboard',
                '@site_title',
            ],
            [
                $subscription_details->name,
                $subscription_details->email,
                $subscription_details->id,
                amount_with_currency_symbol($subscription_details->package_price),
                optional($subscription_details->package)->getTranslation('title',self::lang()),
                str_replace('-','_',$subscription_details->package_gateway),
                $subscription_details->payment_status,
                $subscription_details->created_at->format('D,  d-m-y h:i:s'),
                $subscription_details->tenant_id,
                self::SubscriptionHeadingInfo($subscription_details,$type),
                self::SubscriptionTableInfo($subscription_details,$type),
                $subscription_details->start_date,
                Carbon::parse($subscription_details->expire_date)->format('d-m-Y h:i:s'),
                ' <a href="'.route('landlord.user.home').'">'.__('Your Dashboard').'</a>',
                get_static_option('site_' . self::lang() . '_title')
            ], $message);

        return $message;
    }

    private static function SubscriptionHeadingInfo($subscription_details,$type)
    {
        return view('emailtemplate::landlord.subscription-email-markup.header-info',compact('subscription_details','type'))->render();
    }

    private static function SubscriptionTableInfo($subscription_details,$type)
    {
        return view('emailtemplate::landlord.subscription-email-markup.details-info',compact('subscription_details','type'))->render();
    }



}
