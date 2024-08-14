<?php

namespace App\Http\Controllers\Landlord\Admin;

use App\Events\TenantCronjobEvent;
use App\Events\TenantNotificationEvent;
use App\Events\TenantRegisterEvent;
use App\Helpers\SeederHelpers\JsonDataModifier;
use App\Helpers\TenantHelper\TenantHelpers;
use App\Helpers\FlashMsg;
use App\Helpers\ResponseMessage;
use App\Http\Controllers\Controller;
use App\Mail\BasicDynamicTemplateMail;
use App\Mail\BasicMail;
use App\Mail\PlaceOrder;
use App\Mail\TenantCredentialMail;
use App\Models\CronjobLog;
use App\Models\CustomDomain;
use App\Models\PackageHistory;
use App\Models\PaymentLogHistory;
use App\Models\PaymentLogs;
use App\Models\PricePlan;
use App\Models\Tenant;
use App\Models\Themes;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Modules\EmailTemplate\Traits\EmailTemplate\Landlord\SubscriptionEmailTemplate;
use Spatie\Activitylog\Models\Activity;
use App\Helpers\Payment\DatabaseUpdateAndMailSend\LandlordPricePlanAndTenantCreate;

class TenantManageController extends Controller
{
    const BASE_PATH = 'landlord.admin.tenant.';

    public function all_tenants()
    {
        $all_users = User::latest()->paginate(10);
        $themes = Themes::select('id', 'title', 'slug', 'is_available')->get();
        return view(self::BASE_PATH . 'index', compact('all_users', 'themes'));
    }

    public function new_tenant()
    {
        $countries = \Modules\CountryManage\Entities\Country::select('id', 'name')->get();
        return view(self::BASE_PATH . 'new', compact('countries'));
    }

    public function new_tenant_store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'country' => 'nullable',
            'city' => 'nullable',
            'mobile' => 'nullable',
            'state' => 'nullable',
            'address' => 'nullable',
            'image' => 'nullable',
            'company' => 'nullable',
            'username' => 'required|unique:users',
        ]);


        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'username' => Str::slug($request->username),
            'subdomain' => Str::slug($request->subdomain),
            'country' => $request->country,
            'city' => $request->city,
            'mobile' => $request->mobile,
            'state' => $request->state,
            'address' => $request->address,
            'image' => $request->image,
            'company' => $request->company,
        ]);

        //Notification Event
        $event_data = ['id' => $user->id, 'title' => __('New user registered..!'), 'type' => 'user_registration',];
        event(new TenantNotificationEvent($event_data));
        //Notification Event

       // todo Send account info to the user by mail
        $data['subject'] = __('New Account Registration');
        $data['message'] = __('Welcome') . '<br>';
        $data['message'] .= __('New Account has been created for you '). '<br>';
        $data['message'] .= __('Email:').' '.$request->email. '<br>';
        $data['message'] .= __('Password:').' '.$request->password;

        // todo send account created mail to the user
        try {
            Mail::to($request->email)->send(new BasicMail($data['message'],$data['subject']));
        }catch (\Exception $e){

        }

        return response()->success(ResponseMessage::success(__('Tenant has been created successfully..!')));

    }

    public function edit_profile($id)
    {
        $user = User::find($id);
        $countries = \Modules\CountryManage\Entities\Country::select('id', 'name')->get();
        return view(self::BASE_PATH . 'edit', compact('user', 'countries'));
    }

    public function update_edit_profile(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users', 'email')->ignore($request->id)],
            'username' => ['required', 'string', 'max:255', Rule::unique('users', 'username')->ignore($request->id)],
            'country' => 'nullable',
            'city' => 'nullable',
            'mobile' => 'nullable',
            'state' => 'nullable',
            'address' => 'nullable',
            'image' => 'nullable',
            'company' => 'nullable',
        ]);

        User::where('id', $request->id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'username' => Str::slug($request->username),
            'country' => $request->country,
            'city' => $request->city,
            'mobile' => $request->mobile,
            'state' => $request->state,
            'address' => $request->address,
            'image' => $request->image,
            'company' => $request->company,
        ]);

        return response()->success(ResponseMessage::success(__('Tenant updated successfully..!')));

    }

    public function delete($id)
    {

        $user = User::findOrFail($id);

        $tenants = Tenant::where('user_id', $user->id)->get();
        //media directory delete
        foreach ($tenants ?? [] as $tenant) {

            try {
                TenantHelpers::init()->setUser($user)->setTenantId($tenant->id)->deleteTenant();
            }catch (\Exception $e){}
        }
        $user->delete();
        return response()->danger(ResponseMessage::delete(__('Tenant deleted successfully..!')));
    }

    public function update_change_password(Request $request)
    {
        $this->validate(
            $request, [
            'password' => 'required|string|min:8|confirmed'
        ],
            [
                'password.required' => __('password is required'),
                'password.confirmed' => __('password does not matched'),
                'password.min' => __('password minimum length is 8'),
            ]
        );
        $user = User::findOrFail($request->ch_user_id);
        $user->password = Hash::make($request->password);
        $user->save();
        return response()->success(ResponseMessage::success(__('Password updated successfully..!')));
    }


    public function send_mail(Request $request)
    {

        $this->validate($request, [
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required',
        ]);

        $sub = $request->subject;
        $msg = $request->message;

        try {
            Mail::to($request->email)->send(new BasicMail($msg, $sub));
        } catch (\Exception $ex) {
            return response()->danger(ResponseMessage::delete($ex->getMessage()));
        }

        return response()->success(ResponseMessage::success(__('Mail Send Successfully..!')));
    }

    public function resend_verify_mail(Request $request)
    {
        $subscriber_details = User::findOrFail($request->id);
        $token = $subscriber_details->email_verify_token ? $subscriber_details->email_verify_token : Str::random(8);

        if (empty($subscriber_details->email_verify_token)) {
            $subscriber_details->email_verify_token = $token;
            $subscriber_details->save();
        }
        $message = __('Verification Code: ') . '<strong>' . $token . '</strong>' . '<br>' . __('Verify your email to get all news from ') . get_static_option('site_' . get_default_language() . '_title') . '<div class="btn-wrap"> <a class="anchor-btn" href="' . route('landlord.user.login') . '">' . __('Login') . '</a></div>';

        $msg = $message;
        $subject = __('verify your email');


        try {
            Mail::to($subscriber_details->email)->send(new BasicMail($msg, $subject));
        } catch (\Exception $ex) {
            return response()->danger(ResponseMessage::delete($ex->getMessage()));
        }

        return response()->success(ResponseMessage::success(__('Email Verify Mail Send Successfully..!')));
    }

    public function tenant_activity_log()
    {
        $activities = Activity::with(['subject', 'causer'])->latest()->get();
        return view(self::BASE_PATH . 'activity-log', compact('activities'));
    }

    public function tenant_activity_log_delete($id)
    {
        DB::table('tenant_activity_log')->where('id', $id)->delete();
        return response()->success(ResponseMessage::success(__('Logs cleared successfully..!')));
    }

    public function tenant_activity_log_all_delete()
    {
        DB::table('tenant_activity_log')->delete();
        return response()->success(ResponseMessage::success(__('All Logs cleared successfully..!')));
    }


    public function tenant_details($id)
    {
        $user = User::with('tenant_details', 'tenant_details.payment_log')->findOrFail($id);

        return view(self::BASE_PATH . 'details', compact('user'));
    }

    public function tenant_domain_delete($tenant_id)
    {

        $tenant = Tenant::with('user')->findOrFail($tenant_id);

        try {
            TenantHelpers::init()->setTenantId($tenant_id)->setUser($tenant->user)->deleteTenant();
        }catch (\Exception $e){
            return back()->with(['msg' => $e->getMessage(),'type' => 'danger']);
        }

        return response()->danger(ResponseMessage::delete(__('Tenant deleted successfully..!')));
    }

    public function tenant_account_status(Request $request)
    {
        $request->validate([
            'payment_log_id' => 'required',
            'account_status' => 'required',
            'payment_status' => 'required',
        ]);

        PaymentLogs::findOrFail($request->payment_log_id)->update([
            'status' => $request->account_status,
            'payment_status' => $request->payment_status
        ]);

        return back()->with(ResponseMessage::success(__('Tenant account status is updated..')));
    }

    public function assign_subscription(Request $request)
    {
        $request->validate([
            'package' => 'required',
            'payment_status' => 'required',
            'theme' => 'required_if:subdomain,!=,custom_domain__dd',
            'custom_subdomain' => 'required_if:subdomain,==,custom_domain__dd',
        ], [
            'package.required' => __('select subscription package for the website'),
            'custom_subdomain.required_if' => __('enter your subdomain name for creating website'),
            'theme.required_if' => __('select theme for website creating website')
        ]);

        //todo optimise code for assign subscription
        $user = User::findOrFail($request->subs_user_id);
        $package = PricePlan::findOrFail($request->subs_pack_id);


        $subdomain = $request->custom_subdomain != null ? $request->custom_subdomain : $request->subdomain;

        $old_tenant_log = PaymentLogs::where(['user_id' => $user->id, 'tenant_id' => $subdomain])->latest()->first();

        $tenantHelper = TenantHelpers::init()->setTenantId($subdomain)
            ->setPackage($package)
            ->setPaymentLog($old_tenant_log)
            ->setTheme($request->theme);

        if (empty($package)) {
            return response()->success(ResponseMessage::delete(__('Subscription Package Not Found')));
        }

        if ($tenantHelper->getPackageType() === 'custom') {
            $request->validate([
                'custom_expire_date' => 'required',
            ], ['custom_expire_date.required' => __('Please select custom package expire date')]);
        }

        $custom_expire_date = $request->custom_expire_date;
        $request_theme_slug_or_default = $request->theme;
        try {
            $request_theme_slug_or_default = $tenantHelper->isThemeAvailableForThisPlanFeature();
        } catch (\Exception $e) {
            return redirect()->back()->with(FlashMsg::item_delete($e->getMessage()));
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

        $theme_code = '';
        $package_start_date = $tenantHelper->getStartDate();
        $package_expire_date = $tenantHelper->getExpiredDate($package_expire_date);

        $tenant = Tenant::find($subdomain);
        if (!empty($tenant)) {
            //todo update tenant package
            $old_tenant_log = PaymentLogs::where(['user_id' => $user->id, 'tenant_id' => $tenant->id])->latest()->first();

            if ($package_expire_date != null) {

                $old_days_left = Carbon::now()->diff($old_tenant_log->expire_date ?? '');
                $left_days = 0;

                if ($old_days_left->invert == 0) {
                    $left_days = $old_days_left->days;
                }

                $renew_left_days = 0;
                $renew_left_days = Carbon::parse($package_expire_date)->diffInDays();
                $sum_days = $left_days + $renew_left_days;
                $new_package_expire_date = Carbon::today()->addDays($sum_days)->format("d-m-Y h:i:s");
            } else {
                return back()->with(['msg' => __('Your can not assigned subscription to lifeTime package tenant'),'type' => 'danger']);
            }

            $existingPackageTenantHelper = TenantHelpers::init()
                ->setTenantId($subdomain)
                ->setPackage($package)
                ->setPaymentLog($old_tenant_log)
                ->setUser($user)
                ->setIsRenew(true);
            if ($existingPackageTenantHelper->checkIsSameUser()) {

                $existingPackageTenantHelper->paymentLogUpdate([
                    'start_date' => $package_start_date,
                    'expire_date' => $package_expire_date,
                    'updated_at' => Carbon::now()
                ]);
                $existingPackageTenantHelper->tenantUpdate([
                    'start_date' => $package_start_date,
                    'expire_date' => $package_expire_date,
                    'updated_at' => Carbon::now(),
                ]);

                $payment_details = $existingPackageTenantHelper->getPaymentLog();

                LandlordPricePlanAndTenantCreate::store_payment_log_history($payment_details->id);
                //Notification Event
                $event_data = ['id' => $payment_details->id, 'title' => __('Package subscription renewed by admin'), 'type' => 'package_renew',];
                event(new TenantNotificationEvent($event_data));
                //Notification Event
            }
        } else {

            $tenantHelperForCreateNewWebsite = TenantHelpers::init()
                ->setTenantId($subdomain)
                ->setUser($user)
                ->setPackage($package);

            $tenantHelperForCreateNewWebsite->createPaymentLog([
                'payment_status' => $request->payment_status,
                'theme' => $request_theme_slug_or_default,
                'theme_code' => $theme_code,
            ]);

            $payment_details = $tenantHelperForCreateNewWebsite->getPaymentLog();

            LandlordPricePlanAndTenantCreate::store_payment_log_history($payment_details->id);

            try {
                event(new TenantRegisterEvent($user, $subdomain, $request_theme_slug_or_default));
                $tenantHelperForCreateNewWebsite->tenantUpdate([
                    'expire_date' => $package_expire_date,
                    'theme_slug' => $request_theme_slug_or_default,
                ]);
                //Event Notification
                $event_data = ['id' => $payment_details->id, 'title' => __('New subscription assigned by admin'), 'type' => 'new_subscription',];
                event(new TenantNotificationEvent($event_data));
                //Event Notification
                return back()->with(['msg' => __('Subscription assigned successfully'),'type' => 'success']);
            } catch (\Exception $ex) {
                return $tenantHelperForCreateNewWebsite->trackTenantCreateErrors($ex);
            }
            $tenantHelperForCreateNewWebsite->sendCredentialsToTenant();

            return back()->with(['msg' => __('Subscription assigned successfully'),'type' => 'success']);
        }

        $mail_notification_message = TenantHelpers::init()->sendNotificationEmailToUser($payment_details ?? '');

        return response()->success(ResponseMessage::success(__('Subscription assigned for this user') . ' ' . $mail_notification_message));
    }


    public function account_settings()
    {
        return view(self::BASE_PATH . 'settings');
    }

    public function account_settings_update(Request $request)
    {
        $request->validate([
            'tenant_account_delete_notify_mail_days' => 'required',
            'account_remove_day_within_expiration' => 'required|alpha_num|min:1',
        ]);

        $limit_days = 15;

        if ($request->account_remove_day_within_expiration >= $limit_days) {
            return redirect()->back()->with(['type' => 'danger', 'msg' => sprintf('You can not set remove account day avobe %d', $limit_days)]);
        }
        update_static_option('tenant_account_delete_notify_mail_days', json_encode($request->tenant_account_delete_notify_mail_days));
        update_static_option('account_remove_day_within_expiration', $request->account_remove_day_within_expiration);

        return response()->success(ResponseMessage::success());
    }

    public function tenant_cronjob_log()
    {
        $cronjobs = CronjobLog::orderByDesc('id')->get();
        return view(self::BASE_PATH . 'cronjob-log', compact('cronjobs'));
    }

    public function tenant_cronjob_log_delete($id)
    {
        DB::table('cronjob_logs')->where('id', $id)->delete();
        return response()->success(ResponseMessage::success(__('Logs cleared successfully..!')));
    }

    public function tenant_cronjob_log_all_delete()
    {
        DB::table('cronjob_logs')->delete();
        return response()->success(ResponseMessage::success(__('All Logs cleared successfully..!')));
    }


    public function email_verify_status_update(Request $request)
    {
        User::where('id', $request->user_id)->update([
            'email_verified' => $request->email_verified == 0 ? 1 : 0
        ]);
        return redirect()->back()->with(['msg' => __('Email Verify Status Changed..'), 'type' => 'success']);
    }

    public function get_theme_via_ajax(Request $request)
    {
        $package_id = $request->package_id;
        $package = (object)[];
        if (!empty($package_id)) {
            $package = PricePlan::find($package_id);

            $data_base_features = $package->plan_features?->pluck('feature_name')->toArray() ?? [];
            //Themes
            $json_all_themes = getAllThemeDataForAdmin();
            $themes = [];
            foreach ($json_all_themes as $theme) {
                if (in_array('theme-' . $theme->slug, $data_base_features)) {
                    $themes[] = $theme;
                }
            }
            //Themes

            $theme_view = view('landlord.frontend.user.dashboard.theme-string-blade-data', compact('themes'))->render();

            return response()->json([
                'theme_markup' => $theme_view,
            ]);
        }
    }

    public function update_website_instruction_status(Request $request)
    {
        $tenant_id = $request->id;
        $status = $request->instruction_status;

//        DB::table('tenants')->where('id',$tenant_id)->update([
//            'instruction_status' => !empty($status) ? 1 : 0
//        ]);

        Tenant::where("id", $tenant_id)->update([
            'instruction_status' => !empty($status) ? 1 : 0
        ]);

//        $tenant = Tenant::findOrFail($tenant_id);
//        $tenant->instruction_status = 0;
//        $tenant->save();

        return response()->success(ResponseMessage::success(__('Instruction Status Changed..!')));
    }


}
