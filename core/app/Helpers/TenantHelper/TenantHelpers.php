<?php

namespace App\Helpers\TenantHelper;

use App\Helpers\FlashMsg;
use App\Helpers\Payment\DatabaseUpdateAndMailSend\LandlordPricePlanAndTenantCreate;
use App\Helpers\ResponseMessage;
use App\Mail\BasicDynamicTemplateMail;
use App\Mail\BasicMail;
use App\Mail\PlaceOrder;
use App\Mail\TenantCredentialMail;
use App\Models\Coupon;
use App\Models\CouponLog;
use App\Models\CustomDomain;
use App\Models\PackageHistory;
use App\Models\PaymentLogHistory;
use App\Models\PaymentLogs;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Modules\EmailTemplate\Traits\EmailTemplate\Landlord\SubscriptionEmailTemplate;
use phpDocumentor\Reflection\DocBlock\Tags\Method;

class TenantHelpers
{
    private $tenant_id;
    private $package; //price plan
    private $theme;
    private $tenant;
    private $instance = null;
    private bool $isRenew = false;
    private $user = null;
    private mixed $paymentLog = null;
    private bool $isTrial = false;
    private array $couponInfo = [
        'coupon_id' => null,
        'coupon_discount' => null
    ];
    private $freePlanCount=0;
    private mixed $packageHistory;
    private $paymentLogHistory;

    public static function init()
    {
        $instance = (new self())->instance;
        if (is_null($instance)) {
            return new self();
        }
        return $instance;
    }

    public function setTenant($tenant){
        $this->tenant = $tenant;
        return $this;
    }

    public function setTenantId($tenant_id)
    {
        $this->tenant_id = Str::slug($tenant_id, '-', null);
        return $this;
    }

    public function getTenantId()
    {
        return $this->tenant_id;
    }

    public function getTenant()
    {
        if (is_null($this->tenant)){
            return Tenant::find($this->tenant_id);
        }
        return $this->tenant;
    }

    public function setTheme($theme)
    {
        $this->theme = $theme;
        return $this;
    }

    public function getTheme()
    {
        return $this->theme;
    }

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setPackage($package)
    {
        $this->package = $package;
        return $this;
    }

    public function getPackage()
    {
        return $this->package;
    }

    public function paymentLogUpdate(array $data = [],$updateOnlyGivenField=false)
    {
            $old_tenant_log = $this->getPaymentLog();
            $package = $this->getPackage();
            //if it asked for only update given array data
            $finalData =  array_merge([
            'renew_status' => is_null($old_tenant_log->renew_status) ? 1 : $old_tenant_log->renew_status + 1,
            'is_renew' => 1,
            'track' => Str::random(10) . Str::random(10),
            'updated_at' => Carbon::now(),
            'package_name' => $package->getTranslation('title', get_user_lang()),
            'package_price' => $package->price,
            'package_gateway' => null,
            'package_id' => $package->id,
            'tenant_id' => $this->getTenantId(),
            'status' => $package->has_trial == 1 ? 'trial' : 'complete',
            'payment_status' => request()->payment_status,
            'coupon_id' => $this->getCouponAdditionalInformation()['coupon_id'] ?? null,// ?? null, //get coupon from tenant helpers
            'coupon_discount' => $this->getCouponAdditionalInformation()['coupon_discount'] ?? null,
        ], $data);
        if ($updateOnlyGivenField){
            $finalData = $data;
        }

        $this->setPaymentLog(tap(PaymentLogs::findOrFail($old_tenant_log->id))->update($finalData));

        return $this;
    }

    public function setPaymentLog($old_tenant_log)
    {
        $this->paymentLog = $old_tenant_log;
        return $this;
    }

    public function getPaymentLog()
    {
        return $this->paymentLog;
    }

    public function tenantUpdate(array $data = [],$updateOnlyGivenField=false)
    {
        $tenant = $this->getTenant();

        if (is_null($tenant)) return;

        $finalData = array_merge([
            'renew_status' => $renew_status = is_null($tenant->renew_status) ? 0 : $tenant->renew_status + 1,
            'is_renew' => $renew_status == 0 ? 0 : 1,
        ], $data);

        if ($updateOnlyGivenField){
            $finalData = $data;
        }

        DB::table('tenants')->where('id', $this->getTenantId())->update($finalData);

        return $this;
    }

    public function sendWebsiteCreatingErrorMailToAdmin()
    {
        $admin_mail_message = sprintf(__('Database Crating failed for user id %1$s , please checkout admin panel and generate database for this user trial from admin panel manually'), $this->getPaymentLog()->user_id);
        $admin_mail_subject = sprintf(__('Database Crating failed on trial request for user id %1$s'), $this->getPaymentLog()->user_id);
       try{
           Mail::to(get_static_option('site_global_email'))->send(new BasicMail($admin_mail_message, $admin_mail_subject));
       }catch (\Exception $e){
           Log::alert('Email send failed to admin regarding the website error '. $e->getMessage());
       }

    }

    public function createPaymentLog(array $data = [])
    {
        $package = $this->getPackage();
        $user = $this->getUser();
        $newPaymentLog = PaymentLogs::create(array_merge([
            'email' => $user->email,
            'name' => $user->name,
            'package_name' => $package->getTranslation('title', get_user_lang()),
            'package_price' => $package->price,
            'package_gateway' => null,
            'package_id' => $package->id,
            'user_id' => $user->id,
            'tenant_id' => $this->getTenantId(),
            'status' => 'complete',
            'is_renew' => 0,
            'track' => Str::random(10) . Str::random(10),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'start_date' => $this->getStartDate(),
            'coupon_id' => $this->getCouponAdditionalInformation()['coupon_id'] ?? null,// ?? null, //get coupon from tenant helpers
            'coupon_discount' => $this->getCouponAdditionalInformation()['coupon_discount'] ?? null,
            'assign_status' => 1
        ], $data));
        $this->setPaymentLog($newPaymentLog);
        return $this;
    }

    public function trackTenantCreateErrors(\Exception $ex)
    {
        $message = $ex->getMessage();
        if (strpos($message, 'Access denied')) {
            $this->sendErrorEmailToAdmin();
            $payment_details = $this->getPaymentLog();
            LandlordPricePlanAndTenantCreate::store_exception($payment_details->tenant_id, 'domain create failed', $message, 0);
            return redirect()
                ->back()
                ->with(FlashMsg::item_delete(__('You have no permission to create database, we have created an issue, please go to website settings and manually generate this..!')));
        }
    }

    public function sendCredentialsToTenant()
    {
        $user = $this->getUser();
        $payment_details = $this->getPaymentLog();

        $raw_pass = get_static_option_central('landlord_default_tenant_admin_password_set') ?? '12345678';
        $credential_password = !empty(get_static_option_central('tenant_seeding_password_status')) ? \session()->get('random_password_for_tenant') : $raw_pass;
        $credential_email = $user->email;
        $credential_username = get_static_option_central('landlord_default_tenant_admin_username_set') ?? 'super_admin';

        $lang = get_user_lang();
        $user_dynamic_mail_sub = get_static_option('subscription_order_credential_mail_user_' . $lang . '_subject');
        $user_dynamic_mail_mess = get_static_option('subscription_order_credential_mail_user_' . $lang . '_message');

        try {
            //User Credential Mail
            if (!empty($user_dynamic_mail_sub) && !empty($user_dynamic_mail_mess)) {
                Mail::to($credential_email)->send(new BasicDynamicTemplateMail(SubscriptionEmailTemplate::SubscriptionCredentialMail($payment_details)));
            } else {
                Mail::to($credential_email)->send(new TenantCredentialMail($credential_username, $credential_password));
            }

        } catch (\Exception $e) {
        }

    }

    public function sendNotificationEmailToUser($payment_details)
    {
        if (empty($payment_details)) return;
        $order_mail = get_static_option('order_page_form_mail');
        $order_mail = !empty($order_mail) ? $order_mail : get_static_option('site_global_email');


        $lang = get_user_lang();
        $admin_dynamic_mail_sub = get_static_option('subscription_order_mail_admin_' . $lang . '_subject');
        $admin_dynamic_mail_mess = get_static_option('subscription_order_mail_admin_' . $lang . '_message');

        $user_dynamic_mail_sub = get_static_option('subscription_order_mail_user_' . $lang . '_subject');
        $user_dynamic_mail_mess = get_static_option('subscription_order_mail_user_' . $lang . '_message');
        $mail_notification_message = '';
        try {
            $all_fields = [];
            $all_attachment = [];

            //Admin Mail
            if (!empty($admin_dynamic_mail_sub) && !empty($admin_dynamic_mail_mess)) {
                Mail::to($order_mail)->send(new BasicDynamicTemplateMail(SubscriptionEmailTemplate::SubscriptionAdminMail($payment_details)));
            } else {
                Mail::to($order_mail)->send(new PlaceOrder($all_fields, $all_attachment, $payment_details, "admin", 'regular'));
            }

            //User Mail
            if (!empty($user_dynamic_mail_sub) && !empty($user_dynamic_mail_mess)) {
                Mail::to($payment_details->email)->send(new BasicDynamicTemplateMail(SubscriptionEmailTemplate::SubscriptionUserMail($payment_details)));
            } else {
                Mail::to($payment_details->email)->send(new PlaceOrder($all_fields, $all_attachment, $payment_details, 'user', 'regular'));
            }

        } catch (\Exception $e) {
            $mail_notification_message = __('email notification send failed to user, regarding this website creation, please make sure your smtp is working properly, and user has a valid email address.');
        }
        return $mail_notification_message;
    }

    public function checkFreePlanLimitOver()
    {
        if ($this->getPackage()->price != 0) {
            return false;
        }

        /**
         * PackageHistory, only keep data of 0price plan
         */
        $count_free_pack = PackageHistory::where('user_id', $this->getUser()->id)->count();
        $this->freePlanCount = $count_free_pack;
        $admin_allows = get_static_option('how_many_times_can_user_take_free_or_zero_package') ?? 1;
        abort_if($count_free_pack > 0 && $count_free_pack >= $admin_allows, 501, __('You can not take free package more than') . ' ' . $admin_allows);

        return false;
    }


    public function checkTrialLimit()
    {
        $has_trial = false;
        $tenant_data = $this->getUser()->tenant_details ?? null;

        $getTrialLimit = 1;
        if (is_null($tenant_data)) {
            return $has_trial;
        }
        $numberOfTrial = 0;
        foreach ($tenant_data as $tenant) {
            if (optional($tenant->payment_log)->status == 'trial') {
                $numberOfTrial++;
            }
        }
        return !($getTrialLimit > $numberOfTrial);
    }

    public function setIsTrial(bool $true)
    {
        $this->isTrial = $true;
        return $this;
    }

    public function getIsTrial()
    {
        return $this->isTrial;
    }

    public function setIsRenewFromRequest()
    {
        $this->isRenew = request()->subdomain !== 'custom_domain__dd';
        return $this;
    }

    public function getIsRenew()
    {
        return $this->isRenew;
    }

    public function getExistingPackageType()
    {
        $exising_lifetime_plan = PaymentLogs::with('package')->where(['tenant_id' => $this->getTenantId(), 'payment_status' => 'complete'])->latest()->first();

        return is_null($exising_lifetime_plan) ? 'unknown' : $this->getPackageType($exising_lifetime_plan->package);
    }

    public function isSubdomainUsed()
    {
        $has_subdomain = Tenant::find(trim($this->getTenantId()));
        return !is_null($has_subdomain);
    }

    public function checkCouponFromResponse()
    {
        //todo make an helper for check coupon and return coupon amount.
        $package_price = $this->getPackage()->price;
        if (is_null(request()->coupon_code)) {
            return $package_price;
        }

        $fetch_coupon = Coupon::where(['status' => 1, 'code' => request()->coupon_code])->first();

        //throw error if coupon not found in database
        abort_if(is_null($fetch_coupon), 501, __('Invalid Coupon..!'));

        $only_coupon_discount_amount = !empty($fetch_coupon) ? get_amount_after_landlord_coupon_apply_discount($package_price, $fetch_coupon->code) : 0;
        $maximum_use_fetch = CouponLog::where(['coupon_id' => $fetch_coupon->id, 'user_id' =>$this->getUser()->id])->count();

        //throw error if coupon is already expired
        abort_if($fetch_coupon->expire_date <= now(), 501, __('Coupon Expired..!'));

        //throw error if coupon maximum user limit is over
        abort_if(!empty($maximum_use_fetch) && $maximum_use_fetch >= $fetch_coupon->max_use_qty, 501, sprintf(__('Coupon Limit is over, maximum access limit is %d'), $fetch_coupon->max_use_qty));

        //todo update coupon information to this instance
        $this->setCouponAdditionalInformation($fetch_coupon,$only_coupon_discount_amount);

        return get_amount_after_landlord_coupon_apply($package_price, $fetch_coupon->code);
    }

    public function setCouponAdditionalInformation($coupon,$discounted_amount){
        $this->couponInfo = [
            'coupon_id' => $coupon->id,
            'coupon_discount' => $discounted_amount
        ];
    }

    public function getCouponAdditionalInformation(){
        return $this->couponInfo;
    }

    public function getSelectedPaymentGatewayFromRequest($amount_to_charge)
    {
        $selected_payment_gateway = request()->selected_payment_gateway;
        if ($this->getPackage()->price == 0 && $selected_payment_gateway != 'bank_transfer') {
            $selected_payment_gateway = 'bank_transfer';
        }

        if ($amount_to_charge == 0) {
            $selected_payment_gateway = 'free';
        }

        return $selected_payment_gateway;
    }

    public function createPackageHistory(array $data)
    {
        $this->packageHistory = PackageHistory::create(array_merge([
            'tenant_domain' => $this->getTenantId(),
            'payment_log_id' => $this->getPaymentLog()->id,
            'user_id' => $this->getUser()->id,
            'trial_status' => 0,
            'trial_qty' => 0,
            'zero_price_status' => 1,
            'zero_package_qty' => $this->getFreePlanCount() + 1,
        ],$data));

        return $this;
    }
    public function deleteTenant(){

        $tenant = $this->getTenant();
        if (is_null($tenant)) return;
        $user_id = $this->getUser()->id;

        /**
         * @action TenantDelete
         * Delete Custom Fonts Folder
         * */
        $this->deleteCustomFonts();

        /**
         * @action TenantDelete
         * Delete Dynamic Styles
         * */
        $this->deleteDynamicStyles();

        /**
         * @action TenantDelete
         * Delete Dynamic Script
         * */
        $this->deleteDynamicScript();


        /**
         * @action TenantDelete
         * Delete Custom Domain
         * */
        CustomDomain::where([['old_domain', $this->getTenantId()], ['custom_domain_status', '!=','connected']])
            ->orWhere([['custom_domain', $this->getTenantId()], ['custom_domain_status', '==', 'connected']])->delete();
        PaymentLogs::where('tenant_id', $this->getTenantId())->delete();
        PaymentLogHistory::where('tenant_id', $this->getTenantId())->delete();
        PackageHistory::where('user_id',$user_id)->delete();


        if(!empty($tenant)){

            try{
                $tenant->domains()->delete();
                $tenant->delete();
            }catch(\Exception $ex){

                $message = $ex->getMessage();
                abort_if(str_contains($message,'Access denied'),501,__('Make sure your database user has permission to delete domain & database'));
                abort_if(str_contains($message,'drop database'),501,__('Data deleted'));
            }
        }


        /**
         * @action DeleteTenant
         * delete media uploader files with folder
         * */
        $this->deleteMediaFiles();

        $check_tenant = Tenant::where('user_id', $user_id)->count();
        if ($check_tenant >! 0) User::findOrFail($user_id)->update(['has_subdomain' => false]);

    }


    public function createPaymentLogHistory(array $data=[],$updateOnlyGivenField=false)
    {
        $payment_log = $this->getPaymentLog();

        if ($payment_log->payment_status !== 'complete'){
            return;
        }

        $finalData = array_merge([
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
        ],$data);

        if ($updateOnlyGivenField){
            $finalData = $data;
        }

        $this->paymentLogHistory = PaymentLogHistory::create($finalData);;


        return $this;
    }

    public function getPaymentLogHistory(){
        return $this->paymentLogHistory;
    }

    public function setFreePlanCount($freePlanCount){
         $this->freePlanCount = $freePlanCount;
        return $this;
    }
    public function getFreePlanCount(){
        return $this->freePlanCount;
    }
    public function getPackageHistory(){
        return $this->packageHistory;
    }
    public function setPackageHistory($packageHistory){
        $this->packageHistory = $packageHistory;
        return $this;
    }

    public function createCouponLog(array $data=[])
    {
        $this->couponLog = CouponLog::create(array_merge([
            'log_id' => $this->getPaymentLog()->id,
            'coupon_id' => $this->getPaymentLog()->coupon_id,
            'user_id' => $this->getPaymentLog()->user_id,
        ],$data));

        return $this;
    }
    public function getCouponLog(){
        return $this->couponLog;
    }

    public function saveManualPaymentAttachment()
    {
        $fileName = '';
        if (request()->hasFile('manual_payment_attachment')){
            $fileName = time() . '.' . request()->manual_payment_attachment->extension();
            request()->manual_payment_attachment->move('assets/uploads/attachment/', $fileName);
        }
        return $fileName;
    }

    public function sendEmailToUserForOrderAdminApprovalNotice()
    {
        try {
            $message = __('Thank you for submitting your order and it is pending for admin approval, we will get back to you soon..!');
            $subject = sprintf(__('Order submitted successfully, order id %1$s'), $this->getPaymentLog()->id);

            $admin_message = __('A subscription has taken by bank transfer, please check the attachment and it is now in admin approval stage ');
            $admin_subject = sprintf(__('Order Placed by bank transfer payment, order id %1$s'), $this->getPaymentLog()->id);

            Mail::to($this->getPaymentLog()->email)->send(new BasicMail($message, $subject));
            Mail::to(get_static_option('site_global_email'))->send(new BasicMail($admin_message, $admin_subject));

        } catch (\Exception $e) {

        }
    }

    public function sendThankYouMailToUserForFreePackageSuccess()
    {
        $message = __('Thank you for submitting your order and enjoy the free service');
        $coupon_id = $this->getCouponAdditionalInformation()['coupon_id'] ?? '';
        $subject = empty($coupon_id) ? __('Zero or free package order complete successfully') : __('Coupon Order submitted successfully');

        $admin_message = empty($coupon_id)  ?  __('A subscription has taken with free package') : __('A subscription has taken by coupon 100 percent discount');
        $admin_subject = empty($coupon_id) ? __('Free package order has been placed') : __('Order placed by coupon 100 percent discount');

        try
        {
            Mail::to($this->getPaymentLog()->email)->send(new BasicMail($message, $subject));
            Mail::to(get_static_option('site_global_email'))->send(new BasicMail($admin_message, $admin_subject));
        }catch (\Exception $e){
            Log::alert('thank you email sending error '.$e->getMessage());
        }
    }

    public function sendOrderStatusChangeMailToTenant()
    {

        $data['subject'] = __('your order status has been changed');
        $data['message'] = __('hello').' '.$this->getPaymentLog()->name .'<br>';
        $data['message'] .= __('your order').' #'.$this->getPaymentLog()->id.' ';
        $data['message'] .= __('status has been changed to').' '.str_replace('_',' ',request()->order_status).'.';

        //send mail while order status change
        try {
            Mail::to($this->getPaymentLog()->email)->send(new BasicMail($data['message'], $data['subject']));
        }catch(\Exception $e){
            Log::alert('Email sending failed for tenant #'.$this->getTenantId().' reason '.$e->getMessage());
        }
    }

    public function tenantExceptionCreate(\Exception $e)
    {
        $exception_message = $e->getMessage();
        if(str_contains($e->getMessage(),'Access denied')){
            $exception_message = __('You have no permission to create database, an issue has been created, please go to website settings and manually generate this..!');
            LandlordPricePlanAndTenantCreate::store_exception($this->getTenantId(),'domain create failed',$e->getMessage(),0);
        }
        if (str_contains($e->getMessage(),'1062 Duplicate entry')){
            $exception_message = __('Database already exits');
        }

        return $exception_message;
    }


    public function sendSubscriptionApproveMailToUser(mixed $payment_logs)
    {
        if (empty($payment_logs)) return;

        $lang = get_user_lang();
        //User manual payment dynamic approval Mail
        $user_manual_payment_dynamic_mail_sub = get_static_option('subscription_order_manual_payment_approved_mail_'.$lang.'_subject');
        $user_manual_payment_dynamic_mail_mess = get_static_option('subscription_order_manual_payment_approved_mail_'.$lang.'_message');

        try {

            if(!empty($user_manual_payment_dynamic_mail_sub) && !empty($user_manual_payment_dynamic_mail_mess)){
                Mail::to($payment_logs->email)->send(new BasicDynamicTemplateMail(SubscriptionEmailTemplate::SubscriptionPaymentAcceptMail($payment_logs)));
            }else{
                $subject = __('Your order payment has been approved');
                $message = __('Your order has been approved.').' #'.$payment_logs->id;
                $message .= ' '.__('Package:').' '.$payment_logs->package_name;
                Mail::to($payment_logs->email)->send(new BasicMail($message, $subject));
            }


        }catch (\Exception $e){
           abort(501,$e->getMessage());
        }
    }

    private function getExpireReamingDays()
    {}

    public function getStartDate()
    {
        return Carbon::now()->format('d-m-Y h:i:s');
    }

    private function getEndDateForMonthlyPlan()
    {
        if ($this->getIsTrial()) {
            return $this->getTrialEndDate();
        }
        $packageLog = $this->getPaymentLog();
        $date = $this->isRenew() ? Carbon::parse($packageLog->expire_date) : Carbon::now();
        return $date->addMonth(1)->format('d-m-Y h:i:s');
    }

    private function getTrialEndDate()
    {
        return \Carbon\Carbon::now()->addDays($this->getPackage()->trial_days)->format('d-m-Y h:i:s');
    }

    private function getEndDateForLifetimePlan()
    {
        return null;
    }

    private function getEndDateForYearlyPlan()
    {
        if ($this->getIsTrial()) {
            return $this->getTrialEndDate();
        }
        $paymentLogs = $this->getPaymentLog();
        $date = $this->isRenew() ? Carbon::parse($paymentLogs->expire_date) : Carbon::now();
        return $date->addYear(1)->format('d-m-Y h:i:s');
    }

    private function getEndDateForCustomPlan()
    {
        $custom_expire_date = request()->custom_expire_date;
        return Carbon::parse($custom_expire_date)->format('d-m-Y h:i:s');
    }

    private function isRenew()
    {
        return $this->isRenew;
    }

    public function setIsRenew($renew)
    {
        $this->isRenew = $renew;
        return $this;
    }

    public function getExpiredDate($absoluteDate=false)
    {
        $packageDetails = $this->getPackage();
        if ($absoluteDate){
            $paymentLogs = $this->getPaymentLog();
            return $absoluteDate ? $absoluteDate : Carbon::parse($paymentLogs->expire_date)->format('d-m-Y H:i:s');
        }

        return match ($packageDetails->type) {
            0 => $this->getEndDateForMonthlyPlan(),
            1 => $this->getEndDateForYearlyPlan(),
            3 => $this->getEndDateForCustomPlan(),
            default => $this->getEndDateForLifetimePlan()
        };
    }

    public function newPackageExpireDate($old_tenant_log_expire_date = null, $package_expire_date = null)
    {
        $old_days_left = Carbon::now()->diff($old_tenant_log_expire_date);
        $left_days = 0;

        if ($old_days_left->invert == 0) {
            $left_days = $old_days_left->days;
        }

        $renew_left_days = 0;
        $renew_left_days = Carbon::parse($package_expire_date)->diffInDays();

        $sum_days = $left_days + $renew_left_days;

        $new_package_expire_date = Carbon::today()->addDays($sum_days)->format("d-m-Y h:i:s");
        return $new_package_expire_date;
    }

    public function getPackageType($package = null)
    {
        $packageDetails = is_null($package) ? $this->getPackage() : $package;

        return match ($packageDetails->type) {
            0 => 'monthly',
            1 => 'yearly',
            3 => 'custom',
            default => 'lifetime'
        };
    }

    public function isThemeAvailableForThisPlanFeature($feature = 'eCommerce')
    {
        $themeName = $this->getTheme();
        $packageDetails = $this->getPackage();

        abort_if(is_null($packageDetails->plan_features), 501, __('Subscription plan must have some features enabled'));

        $all_features = $packageDetails->plan_features ?? [];
        $check_feature_name = $all_features->pluck('feature_name')->toArray();

        abort_if($themeName == 'eCommerce' && !in_array($feature, $check_feature_name), 501, __('Please give ecommerce feature on price plan first for this theme'));

        return $themeName;
    }


    public function checkIsSameUser()
    {
        $old_tenant_log = $this->getPaymentLog();
        $user = $this->getUser();
        return !empty($old_tenant_log->package_id) && !empty($old_tenant_log->user_id) && $old_tenant_log->user_id == $user?->id;
    }

    private function sendErrorEmailToAdmin()
    {
        $payment_details = $this->getPaymentLog();
        $admin_mail_message = sprintf(__('Database Crating failed for user id %1$s , please checkout admin panel and generate database for this user trial from admin panel manually'), $payment_details->user_id);
        $admin_mail_subject = sprintf(__('Database Crating failed on trial request for user id %1$s'), $payment_details->user_id);
        //added in try catch block, because if any user did not configure smtp yet, but trying to create user website, it's throwing 500error
        try {
            Mail::to(get_static_option('site_global_email'))->send(new BasicMail($admin_mail_message, $admin_mail_subject));
        } catch (\Exception $e) {
            //
        }
    }

    public function getTenantIdFromRequest()
    {
        //detect subdomain... //subdomain->always create new website
        //if subdomain === custom_domain__dd , then need to get subdomain value from custom_subdomain field
        return request()->subdomain === 'custom_domain__dd' ? request()->custom_subdomain : request()->subdomain;
    }

    private function deleteCustomFonts()
    {
        $tenant_custom_font_path = 'assets/tenant/frontend/custom-fonts/'.$this->getTenantId().'/';
        if(File::exists($tenant_custom_font_path) && is_dir($tenant_custom_font_path)){
            File::deleteDirectory($tenant_custom_font_path);
        }

    }

    private function deleteDynamicStyles()
    {
        $file_path = 'assets/tenant/frontend/themes/css/dynamic-styles/'.$this->getTenantId().'-style.css';
        if(file_exists($file_path) && is_dir($file_path)){
            @unlink($file_path);
        }
    }

    private function deleteDynamicScript()
    {
        $file_path = 'assets/tenant/frontend/themes/js/dynamic-scripts/'.$this->getTenantId().'-script.js';
        if(file_exists($file_path) && is_dir($file_path)){
            @unlink($file_path);
        }

    }

    private function deleteMediaFiles()
    {
        $path = 'assets/tenant/uploads/media-uploader/'. $this->getTenantId();
        if(File::exists($path) && is_dir($path)){
            File::deleteDirectory($path);
        }
    }


    public function getTenantStartDate(){
        //todo write code if tenant not exists
        if (is_null($this->getTenant())) return __('website not yet created');
        return is_null($this->getTenant()->start_date) ? Carbon::parse($this->getTenant()->created_at)->format($this->getDateTimeFormatSelectedByAdmin()) : Carbon::parse($this->getTenant()->start_date)->format($this->getDateTimeFormatSelectedByAdmin());
    }
    public function getTenantExpiredDate(){
        if (is_null($this->getTenant())) return __('website not yet created');
        return is_null($this->getTenant()->expire_date) ? __('Lifetime') : Carbon::parse($this->getTenant()->expire_date)->format($this->getDateTimeFormatSelectedByAdmin());
    }
    private function getDateTimeFormatSelectedByAdmin(){
        $date_display_style = get_static_option('date_display_style');
        return match ($date_display_style){
                'style_two' => 'd-M-Y',
                'style_three' => 'Y/m/d',
                'style_four' => 'Y-m-d',
                default => 'd-m-Y'
            };
    }

}
