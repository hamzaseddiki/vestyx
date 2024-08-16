<?php

namespace App\Http\Controllers\Landlord\Frontend;

use App\Actions\Tenant\TenantCreateEventWithMail;
use App\Events\TenantNotificationEvent;
use App\Events\TenantRegisterEvent;
use App\Helpers\FlashMsg;
use App\Helpers\Payment\DatabaseUpdateAndMailSend\LandlordPricePlanAndTenantCreate;
use App\Helpers\Payment\PaymentGatewayCredential;
use App\Helpers\TenantHelper\TenantHelpers;
use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Models\PaymentLogs;
use App\Models\PricePlan;
use App\Models\TenantException;
use App\Models\User;
use App\Traits\PaymentLogIpn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Xgenious\Paymentgateway\Facades\XgPaymentGateway;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use App\Models\Tenant;
use App\Models\PackageHistory;
use App\Models\Coupon;
use App\Models\CouponLog;


class PaymentLogController extends Controller
{
    //for all Ipn methods
    use PaymentLogIpn;
    //for all Ipn methods
    private const SUCCESS_ROUTE = 'landlord.frontend.order.payment.success';
    private const STATIC_CANCEL_ROUTE = 'landlord.frontend.order.payment.cancel.static';

    private static function go_home_page()
    {
        if (session()->has('exception_tenant_id')) {
            $session_tenant_id = session()->get('exception_tenant_id');
            if (TenantException::where('tenant_id', $session_tenant_id)->first()) {
                session()->forget('exception_tenant_id');
                return redirect()->route('landlord.user.home')->with(FlashMsg::item_delete(__('Your website is not ready yet, It is in admin approval stage..! you will get notified via email soon..!')));
            }
        }
        return redirect()->route('landlord.homepage');
    }

    public function order_payment_form(Request $request)
    {
        $request_pack_id = $request->package_id;
        $log_id_from_tenant_admin = $request->log_id_from_tenant_admin;

        $condition_for_log_id_from_tenant_1 = !empty($log_id_from_tenant_admin) ? "nullable" : "required_if:custom_subdomain,!=,null";
        $condition_for_log_id_from_tenant_2 = !empty($log_id_from_tenant_admin) ? "nullable" : "required_if:subdomain,==,custom_domain__dd";
        $condition_for_subdomain = !empty($log_id_from_tenant_admin) ? "nullable" : "required";

        $quick_website = $request->quick_web_site;
        $payment_condition =  !is_null($quick_website) && $request->payalbe_amount != 0 ? 'required' : 'nullable';
        $theme_condition =  !is_null($quick_website) ? 'required' : 'nullable';


        if (!is_null($quick_website)) {
            session(['website_create_type' => 'quick_website']);
        }

        $request->validate(
            [
                'name' => 'nullable|string|max:191',
                'email' => 'nullable|email|max:191',
                'package_id' => 'required|string',
                'payment_gateway' => $payment_condition,
                'subdomain' => $condition_for_log_id_from_tenant_1,
                'custom_subdomain' => $condition_for_log_id_from_tenant_2,
                'theme_slug' => $theme_condition,
                'manual_payment_attachment' => 'required_if:selected_payment_gateway,==,bank_transfer|mimes:jpg,jpeg,png,pdf',
                'transaction_id' => 'required_if:selected_payment_gateway,==,manual_payment_'
            ],
            [
                "custom_subdomain.required_if" => "Custom Sub Domain Required",
                "trasaction_id" => "Transaction ID Required",
                "trasaction_attachment" => "Transaction Attachment Required",
                "theme_slug.required" => "The theme selection is required",
                "package_id.required" => "The package selection is required",
            ]
        );


        //package Details
        $package = PricePlan::find($request->package_id);

        if (is_null($package)) {
            return back()->with(['msg' => __('your selected subscription package not found'), 'type' => 'danger']);
        }

        //If Quick website trial
        if (!is_null($request->quick_trial_status) && $request->quick_trial_status == 'trial') {
            unset($request['payment_gateway']);
            return $this->user_trial_action($request, $request->package_id);
        }
        //If Quick website trial

        //todo:: work on website create
        //        $subdomain = TenantHelpers::init()->getTenantIdFromRequest();
        //
        //        if (is_null($subdomain)){
        //            return back()->with(['msg' => __('set subdomain to process further'),'type' => 'danger']);
        //        }

        if (!is_null($log_id_from_tenant_admin)) {
            $subdomain = PaymentLogs::find($log_id_from_tenant_admin)->tenant_id;
        } else {
            if ($request->subdomain != 'custom_domain__dd') {
                $subdomain = Str::slug($request->subdomain);
            } else {
                $subdomain = Str::slug($request->custom_subdomain);
            }
        }

        if (is_null($subdomain)) {
            return back()->with(['msg' => __('set subdomain to process further'), 'type' => 'danger']);
        }


        $user = $auth = auth('web')->user();
        $createNewWebsiteTenantHelper = TenantHelpers::init()
            ->setTenantId($subdomain)
            ->setUser($user)
            ->setIsRenewFromRequest()
            ->setTheme($request->theme_slug)
            ->setPackage($package);

        $subdomain = $createNewWebsiteTenantHelper->getTenantIdFromRequest();

        if (empty($request->theme_slug) && $createNewWebsiteTenantHelper->getIsRenew() === false) {
            return back()->with(['msg' => __('select a theme to create website'), 'type' => 'danger']);
        }

        $custom_expire_date = $request->custom_expire_date;
        $request_theme_slug_or_default = get_static_option_central('landlord_default_theme_set');

        try {
            $request_theme_slug_or_default = $createNewWebsiteTenantHelper->isThemeAvailableForThisPlanFeature();
        } catch (\Exception $e) {
            return redirect()->back()->with(FlashMsg::item_delete($e->getMessage()));
        }
        //todo check if subdomain is has record in payment log means it is renew or new website
        if ($createNewWebsiteTenantHelper->getIsRenew() && $createNewWebsiteTenantHelper->getExistingPackageType() === 'lifetime') {
            return back()->with(['type' => 'danger', 'msg' => __('You are already using a lifetime plan')]);
        }

        if ($createNewWebsiteTenantHelper->isSubdomainUsed() && !$createNewWebsiteTenantHelper->getIsRenew()) {
            return back()->with(['type' => 'danger', 'msg' => __('This subdomain is already in use, Try something different')]);
        }

        try {
            //checking if the free plan limit is over
            $createNewWebsiteTenantHelper->checkFreePlanLimitOver();
        } catch (\Exception $e) {
            return back()->with(['type' => 'danger', 'msg' =>  $e->getMessage()]);
        }

        // todo set package startDate and ExpireDate
        $package_start_date = '';
        $package_expire_date = '';

        if (!empty($package)) {
            if ($package->type == 0) { //monthly
                $package_start_date = Carbon::now()->format('d-m-Y h:i:s');
                $package_expire_date = Carbon::now()->addMonth(1)->format('d-m-Y h:i:s');
            } elseif ($package->type == 1) { //yearly
                $package_start_date = Carbon::now()->format('d-m-Y h:i:s');
                $package_expire_date = Carbon::now()->addYear(1)->format('d-m-Y h:i:s');
            } else { //lifetime
                $package_start_date = Carbon::now()->format('d-m-Y h:i:s');
                $package_expire_date = null;
            }
        }

        // todo set package StartDate and ExpireDate
        $package_start_date = $createNewWebsiteTenantHelper->getStartDate();
        $package_expire_date = $createNewWebsiteTenantHelper->getExpiredDate($package_expire_date);

        // todo Check Coupon
        $amount_to_charge = $createNewWebsiteTenantHelper->getPackage()->price;
        try {
            $amount_to_charge = $createNewWebsiteTenantHelper->checkCouponFromResponse();
        } catch (\Exception $e) {
            return response()->back(['msg' => $e->getMessage(), 'type' => 'danger']);
        }
        //        $selected_payment_gateway = $createNewWebsiteTenantHelper->getSelectedPaymentGatewayFromRequest($amount_to_charge);


        //start working

        $request_date_remove = $request;
        $selected_payment_gateway = '';

        if (!is_null($request->gateway_from_renew_tenant)) {
            $selected_payment_gateway = $request->payment_gateway;
        } else {
            if (!is_null($request_date_remove['selected_payment_gateway'])) {
                $selected_payment_gateway = $request_date_remove['selected_payment_gateway'];
            } else if (!is_null($request_date_remove['payment_gateway'])) {
                $selected_payment_gateway = $request_date_remove['payment_gateway'];
            } else if (is_null($request_date_remove['payment_gateway'])) {
                $selected_payment_gateway = 'bank_transfer';
            }
        }

        if ($amount_to_charge == 0) {
            $selected_payment_gateway = 'free';
        }

        if ($createNewWebsiteTenantHelper->getPackage()->price == 0 && $selected_payment_gateway != 'bank_transfer') {
            $selected_payment_gateway = 'bank_transfer';
        }


        $is_tenant = $createNewWebsiteTenantHelper->getTenant();


        DB::beginTransaction(); // Starting all the actions as safe translations
        try {
            // Exising Tenant + Plan
            if (!is_null($is_tenant)) {
                $old_tenant_log = PaymentLogs::where(['user_id' => $createNewWebsiteTenantHelper->getUser()->id, 'tenant_id' => $is_tenant->id])->latest()->first() ?? '';

                $createNewWebsiteTenantHelper->setPaymentLog($old_tenant_log);

                // If Payment Renewing
                if (
                    !empty($old_tenant_log->package_id) == $request_pack_id && !empty($old_tenant_log->user_id)
                    && $old_tenant_log->user_id == $createNewWebsiteTenantHelper->getUser()->id
                    && $old_tenant_log->payment_status == 'complete'
                ) {
                    if ($package_expire_date != null) {
                        $new_package_expire_date = $createNewWebsiteTenantHelper->newPackageExpireDate($old_tenant_log->expire_date, $package_expire_date);
                    } else {
                        $new_package_expire_date = null;
                    }

                    $createNewWebsiteTenantHelper->paymentLogUpdate([
                        'package_name' => $createNewWebsiteTenantHelper->getPackage()->getTranslation('title', get_user_lang()),
                        'package_price' => $amount_to_charge,
                        'package_gateway' => $selected_payment_gateway ?? 'bank_transfer',
                        'package_id' => $createNewWebsiteTenantHelper->getPackage()->id,
                        'user_id' => $createNewWebsiteTenantHelper->getUser()->id ?? null,
                        'tenant_id' => $createNewWebsiteTenantHelper->getTenantId() ?? null,
                        'status' => $old_tenant_log->status,
                        'payment_status' => $old_tenant_log->payment_status,
                        'renew_status' => is_null($old_tenant_log->renew_status) ? 1 : $old_tenant_log->renew_status + 1,
                        'start_date' => !empty($old_tenant_log->start_date) ? $old_tenant_log->start_date :  $package_start_date,
                        'expire_date' => $selected_payment_gateway == 'bank_transfer' ? $old_tenant_log->expire_date : $new_package_expire_date,
                        'theme' => $old_tenant_log->theme
                    ]);

                    $createNewWebsiteTenantHelper->tenantUpdate([
                        'renew_status' => $createNewWebsiteTenantHelper->getTenant()->renew_status + 1,
                        'is_renew' => 1,
                    ]);

                    //Notification Event
                    $event_data = ['id' => $createNewWebsiteTenantHelper->getPaymentLog()->id, 'title' => __('Package subscription renewed'), 'type' => 'package_renew',];
                    event(new TenantNotificationEvent($event_data));
                    //Notification Event

                } // If Payment Pending
                elseif (!empty($old_tenant_log) && $old_tenant_log->payment_status == 'pending') {
                    if ($package_expire_date != null) {
                        $new_package_expire_date = $createNewWebsiteTenantHelper->newPackageExpireDate($old_tenant_log->expire_date, $package_expire_date);
                    } else {
                        $new_package_expire_date = null;
                    }
                    $createNewWebsiteTenantHelper->paymentLogUpdate([
                        'package_price' => $amount_to_charge,
                        'package_gateway' => $selected_payment_gateway ?? 'bank_transfer',
                        'status' => 'pending',
                        'payment_status' => 'pending',
                        'is_renew' => $old_tenant_log->renew_status != null ? 1 : 0,
                        'start_date' => $old_tenant_log->status == 'trial' ? $old_tenant_log->start_date : $package_start_date,
                        'expire_date' => $old_tenant_log->status == 'trial' ? $new_package_expire_date : $package_expire_date
                    ]);
                }
            } // New Tenant + Plan (New Payment)
            else {
                $old_tenant_log = PaymentLogs::where(['user_id' => $createNewWebsiteTenantHelper->getUser()->id, 'tenant_id' => trim($createNewWebsiteTenantHelper->getTenantId())])->latest()->first();
                $createNewWebsiteTenantHelper->setPaymentLog($old_tenant_log);
                if (is_null($old_tenant_log)) {
                    $createNewWebsiteTenantHelper->createPaymentLog([
                        'package_price' => $amount_to_charge,
                        'package_gateway' => $selected_payment_gateway ?? 'bank_transfer',
                        'status' => 'pending',
                        'payment_status' => 'pending',
                        'expire_date' => $package_expire_date,
                        'theme' => $request_theme_slug_or_default ?? 'donation',
                    ]);
                    //Event Notification
                    $event_data = ['id' => $createNewWebsiteTenantHelper->getPaymentLog()->id, 'title' => __('New subscription plan taken'), 'type' => 'new_subscription',];
                    event(new TenantNotificationEvent($event_data));
                    //Event Notification
                } else {
                    $createNewWebsiteTenantHelper->paymentLogUpdate([
                        'package_price' => $amount_to_charge,
                        'package_gateway' => $selected_payment_gateway ?? 'free',
                        'status' => 'pending',
                        'payment_status' => 'pending',
                        'theme' => $request_theme_slug_or_default ?? 'donation',
                    ]);
                }
            }

            //free package store history
            if ($createNewWebsiteTenantHelper->getPackage()->price == 0) {
                $createNewWebsiteTenantHelper->createPackageHistory([
                    'trial_status' => 0,
                    'trial_qty' => 0,
                    'zero_price_status' => 1
                ]);
            }
            //free package store history

            //Coupon logs store
            if (!empty($createNewWebsiteTenantHelper->getPaymentLog()->coupon_id)) {
                $createNewWebsiteTenantHelper->createCouponLog();
            }
            //Coupon logs store
            DB::commit(); // Committing all the actions
        } catch (\Exception $exception) {
            $message = $exception->getMessage();
            DB::rollBack(); // Rollback all the actions
            return back()->with('msg', $message);
        }

        if ($selected_payment_gateway == 'bank_transfer') {
            if ($createNewWebsiteTenantHelper->getPackage()->price != 0) { //for free zero pack

                $fileName = $createNewWebsiteTenantHelper->saveManualPaymentAttachment();
                $createNewWebsiteTenantHelper->paymentLogUpdate([
                    'manual_payment_attachment' => $fileName,
                    'status' => $createNewWebsiteTenantHelper->getPaymentLog()->status,
                    'payment_status' => $createNewWebsiteTenantHelper->getPaymentLog()->is_renew == 1 ? 'pending' : $createNewWebsiteTenantHelper->getPaymentLog()->payment_status,
                ]);
            }

            $createNewWebsiteTenantHelper->sendEmailToUserForOrderAdminApprovalNotice();

            $order_id = XgPaymentGateway::wrapped_id(random_int(1, 9) . $createNewWebsiteTenantHelper->getPaymentLog()->id . random_int(1, 9));
            return redirect()->route(self::SUCCESS_ROUTE, $order_id);
        } else if ($selected_payment_gateway == 'free') {
            try {

                if (!empty(get_static_option_central('subscription_free_package_auto_approve_status'))) {

                    //todo
                    $createNewWebsiteTenantHelper->paymentLogUpdate([
                        'payment_status' => 'complete',
                        'status' => 'complete',
                        'start_date' => $createNewWebsiteTenantHelper->getPaymentLog()->is_renew == 1 ?  $createNewWebsiteTenantHelper->getPaymentLog()->start_date : $package_start_date,
                        'expire_date' => $createNewWebsiteTenantHelper->getPaymentLog()->is_renew == 1 ?  $createNewWebsiteTenantHelper->getExpiredDate($package_expire_date) : $package_expire_date
                    ], true);
                    event(new TenantRegisterEvent($createNewWebsiteTenantHelper->getUser(), $createNewWebsiteTenantHelper->getTenantId()));
                    $createNewWebsiteTenantHelper->sendCredentialsToTenant();
                    $createNewWebsiteTenantHelper->tenantUpdate([
                        'start_date' => $createNewWebsiteTenantHelper->getPaymentLog()->start_date,
                        'expire_date' =>  $createNewWebsiteTenantHelper->getPaymentLog()->expire_date,
                        'user_id' => $createNewWebsiteTenantHelper->getPaymentLog()->user_id,
                        'theme_slug' => $createNewWebsiteTenantHelper->getPaymentLog()->theme
                    ]);

                    $user = $createNewWebsiteTenantHelper->getUser();
                    $url = DB::table('domains')->where('tenant_id', $createNewWebsiteTenantHelper->getTenantId())->first()->domain;
                    $url = tenant_url_with_protocol($url);
                    $user->update(['has_subdomain' => 1]);

                    $createNewWebsiteTenantHelper->sendThankYouMailToUserForFreePackageSuccess();

                    return redirect()->to($url);
                } else {
                    $createNewWebsiteTenantHelper->sendThankYouMailToUserForFreePackageSuccess();
                    $createNewWebsiteTenantHelper->tenantUpdate([
                        'start_date' => $createNewWebsiteTenantHelper->getPaymentLog()->start_date,
                        'expire_date' =>  $createNewWebsiteTenantHelper->getPaymentLog()->expire_date,
                        'user_id' => $createNewWebsiteTenantHelper->getPaymentLog()->user_id,
                        'theme_slug' => $createNewWebsiteTenantHelper->getPaymentLog()->theme
                    ]);

                    $order_id = XgPaymentGateway::wrapped_id(random_int(1, 9) . $createNewWebsiteTenantHelper->getPaymentLog()->id . random_int(1, 9));
                    return redirect()->route(self::SUCCESS_ROUTE, $order_id);
                }
            } catch (\Exception $e) {
            }
        } else if ($selected_payment_gateway == 'manual_payment_') {
            $createNewWebsiteTenantHelper->paymentLogUpdate([
                'transaction_id' => $request->transaction_id,
                'status' => $createNewWebsiteTenantHelper->getPaymentLog()->status,
                'payment_status' => $createNewWebsiteTenantHelper->getPaymentLog()->is_renew == 1 ? 'pending' : $createNewWebsiteTenantHelper->getPaymentLog()->payment_status,
            ], true);

            $createNewWebsiteTenantHelper->sendEmailToUserForOrderAdminApprovalNotice();
            $order_id = XgPaymentGateway::wrapped_id(random_int(1, 9) . $createNewWebsiteTenantHelper->getPaymentLog()->id . random_int(1, 9));
            return redirect()->route(self::SUCCESS_ROUTE, $order_id);
        } else {
            return $this->payment_with_gateway($selected_payment_gateway, $amount_to_charge, $createNewWebsiteTenantHelper->getPaymentLog(), $request);
        }

        return redirect()->route('landlord.homepage');
    }


    public function payment_with_gateway($selected_payment_gateway, $amount_to_charge, $payment_details, $request)
    {

        $gateway_function = 'get_' . $selected_payment_gateway . '_credential';

        if (!method_exists((new PaymentGatewayCredential()), $gateway_function)) {
            $custom_data['request'] = $request;
            $custom_data['payment_details'] = $payment_details->toArray();
            $custom_data['total'] = $payment_details->package_price;

            //add extra param support to the shop checkout payment system
            $custom_data['payment_type'] = "price_plan";
            $custom_data['payment_for'] = "landlord";
            $custom_data['cancel_url'] = route(self::STATIC_CANCEL_ROUTE);
            $custom_data['success_url'] = route(self::SUCCESS_ROUTE, random_int(111111, 999999) . $payment_details->id . random_int(111111, 999999));


            $charge_customer_class_namespace = getChargeCustomerMethodNameByPaymentGatewayNameSpace($selected_payment_gateway);
            $charge_customer_method_name = getChargeCustomerMethodNameByPaymentGatewayName($selected_payment_gateway);

            $custom_charge_customer_class_object = "";
            if (!empty($charge_customer_class_namespace) && class_exists($charge_customer_class_namespace)) {
                $custom_charge_customer_class_object = new $charge_customer_class_namespace;
            }

            if (class_exists($charge_customer_class_namespace) && method_exists($custom_charge_customer_class_object, $charge_customer_method_name)) {
                try {
                    return $custom_charge_customer_class_object->$charge_customer_method_name($custom_data);
                } catch (\Exception $e) {
                    return back()->with(FlashMsg::explain('danger', $e->getMessage()));
                }
            } else {
                return back()->with(FlashMsg::explain('danger', 'Incorrect Class or Method'));
            }
        } else {

            try {
                $gateway = PaymentGatewayCredential::$gateway_function();

                $params = $this->common_charge_customer_data(
                    $amount_to_charge,
                    $payment_details,
                    $request,
                    route('landlord.frontend.' . $selected_payment_gateway . '.ipn')
                );
                return $gateway->charge_customer($params);
            } catch (\Exception $e) {
                return back()->with(['msg' => $e->getMessage(), 'type' => 'danger']);
            }
        }
    }
    private function common_charge_customer_data($amount_to_charge, $payment_details, $request, $ipn_url): array
    {
        $data = [
            'amount' => $amount_to_charge ?? 1,
            'title' => $payment_details->package_name,
            'description' => 'Payment For Package Order Id: #' . $request->package_id . ' Package Name: ' . $payment_details->package_name .
                'Payer Name: ' . $request->name . ' Payer Email:' . $request->email,
            'order_id' => $payment_details->id,
            'track' => $payment_details->track,
            'cancel_url' => route(self::STATIC_CANCEL_ROUTE),
            'success_url' => route(self::SUCCESS_ROUTE, $payment_details->id),
            'email' => $payment_details->email,
            'name' => $payment_details->name,
            'payment_type' => 'order',
            'ipn_url' => $ipn_url,
        ];

        return $data;
    }
    private function common_ipn_data($payment_data)
    {

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete') {

            $log = [];
            if (!empty($payment_data['order_id'])) {
                $log = PaymentLogs::find($payment_data['order_id']);
            }

            try {
                LandlordPricePlanAndTenantCreate::update_database($payment_data['order_id'], $payment_data['transaction_id']);
            } catch (\Exception $e) {
                return redirect()->back(FlashMsg::item_delete(__('Something went wrong...!')));
            }


            try {
                LandlordPricePlanAndTenantCreate::tenant_create_event_with_credential_mail($payment_data['order_id']);
                session()->forget('random_password_for_tenant');
            } catch (\Exception $e) {

                $message = $e->getMessage();

                $admin_mail_message = sprintf(__('Database Crating failed for user id %1$s , please checkout admin panel and generate database for this user from admin panel manually'), $log->user_id);
                $admin_mail_subject = sprintf(__('Database Crating failed for user id %1$s'), $log->user_id);
                Mail::to(get_static_option('site_global_email'))->send(new BasicMail($admin_mail_message, $admin_mail_subject));

                if (str_contains($message, 'Access denied')) {
                    LandlordPricePlanAndTenantCreate::store_exception($log->tenant_id, 'domain create failed', $message, 0);

                    //Event Notification
                    $event_data = ['id' => $log->id, 'title' => __('Database and domain create failed'), 'type' => 'new_subscription',];
                    event(new TenantNotificationEvent($event_data));
                    //Event Notification

                    //Store tenant id in session
                    session(['exception_tenant_id' => $log->tenant_id]);
                }
            }


            if (DB::table('domains')->where('tenant_id', $log->tenant_id)->first()) {

                try {
                    LandlordPricePlanAndTenantCreate::update_tenant($payment_data);
                } catch (\Exception $e) {

                    LandlordPricePlanAndTenantCreate::store_exception($log->tenant_id, 'database update', $e->getMessage(), 1);
                }

                try {
                    LandlordPricePlanAndTenantCreate::send_order_mail($payment_data['order_id']);
                } catch (\Exception $e) {
                }
            }



            $order_id = wrap_random_number($payment_data['order_id']);

            if (\session()->has('website_create_type')) {
                $url = DB::table('domains')->where('tenant_id', $log->tenant_id)->first()->domain;
                $url = tenant_url_with_protocol($url);
                \session()->forget('website_create_type');

                return redirect()->to($url);
            }

            return redirect()->route(self::SUCCESS_ROUTE, $order_id);
        }

        return redirect()->route(self::STATIC_CANCEL_ROUTE);
    }

    public function user_trial_action($request, $package_id)
    {
        $request->validate([
            'subdomain' => 'required|unique:tenants,id',
            'theme' => 'required',
        ], [
            'theme.required' => 'No theme is selected.'
        ]);

        $user_id = Auth::guard('web')->user()->id;
        $user = User::findOrFail($user_id);
        $package = PricePlan::findOrFail($package_id);
        $subdomain = $request->subdomain;

        $tenantHelper = TenantHelpers::init()
            ->setTenantId($subdomain)
            ->setPackage($package)
            ->setUser($user)
            ->setTheme($request->theme);
        if (empty($package)) {
            return redirect()->back()->with(FlashMsg::item_delete(__('Subscription Package Not Found')));
        }

        //checking eCommerce feature is available or not

        $theme = $request->theme_slug;
        $theme_code = $request->theme_code;
        try {
            $theme = $tenantHelper->isThemeAvailableForThisPlanFeature();
            session()->put('theme', $theme);
        } catch (\Exception $e) {
            return redirect()->back()->with(FlashMsg::item_delete($e->getMessage()));
        }

        //test above code
        if ($tenantHelper->checkTrialLimit()) {
            return back()->with([
                'msg' => __('Your trial limit is over! Please purchase a plan to continue') . '<br>' . '<small>' . __('You can make trial once only..!') . '</small>',
                'type' => 'danger'
            ]);
        }

        $tenantHelper->setIsTrial(true);

        $trial_expire_date = "";
        $plan_trial_days = $tenantHelper->getPackage()->trial_days;
        if (!empty($tenantHelper->getPackage())) {
            $trial_expire_date = Carbon::now()->addDays($plan_trial_days)->format('d-m-Y h:i:s');
        }
        //end

        $tenantHelper->createPaymentLog([
            'package_name' => $package->getTranslation('title', get_user_lang()),
            'package_price' => 0,
            'status' => 'trial',
            'payment_status' => 'pending',
            'start_date' => $tenantHelper->getStartDate(),
            'expire_date' => $trial_expire_date,
            'theme' => $theme,
            'theme_code' => $theme_code,
        ]);

        try {

            TenantCreateEventWithMail::tenant_create_event_with_credential_mail($user, $subdomain);
            session()->forget('random_password_for_tenant');
            $tenantHelper->tenantUpdate([
                'start_date' => $tenantHelper->getStartDate(),
                'expire_date' => $trial_expire_date,
                'theme_slug' => $theme,
                'renew_status' => 0,
                'is_renew' => 0
            ], true);
        } catch (\Exception $ex) {

            $message = $ex->getMessage();
            $tenantHelper->sendWebsiteCreatingErrorMailToAdmin();
            if (!tenant()) {
                LandlordPricePlanAndTenantCreate::store_exception($subdomain, 'domain failed on trial', $message, 0);
            }

            //Event Notification
            $event_data = ['id' => $tenantHelper->getPaymentLog()->id, 'title' => __('Database and domain create failed on trial'), 'type' => 'trial',];
            event(new TenantNotificationEvent($event_data));
            //Event Notification
            return back()->with(['msg' => __('Your trial website is not ready yet, we have notified to admin regarding your request, it is in admin approval stage..!, please try later..!'), 'type' => 'danger']);
        }


        $url = DB::table('domains')->where('tenant_id', $subdomain)->first()->domain;
        $url = tenant_url_with_protocol($url);
        $user->update(['has_subdomain' => 1]);

        //Notification Event
        $event_data = ['id' =>  $tenantHelper->getPaymentLog()->id, 'title' =>  __('Subscription Trial Added'), 'type' =>  'trial',];
        event(new TenantNotificationEvent($event_data));
        //Notification Event

        return redirect()->to($url);
    }
}
