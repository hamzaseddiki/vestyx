<?php

namespace App\Http\Controllers\Landlord\Admin;

use App\Events\TenantCronjobEvent;
use App\Events\TenantNotificationEvent;
use App\Events\TenantRegisterEvent;
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

class TenantManageControllerOLD extends Controller
{
    const BASE_PATH = 'landlord.admin.tenant.';
    public function all_tenants(){
        $all_users = User::latest()->paginate(10);
        $themes = Themes::select('id','title','slug','is_available')->get();
        return view(self::BASE_PATH.'index',compact('all_users','themes'));
    }

    public function new_tenant()
    {
        $countries = \Modules\CountryManage\Entities\Country::select('id','name')->get();
        return view(self::BASE_PATH.'new',compact('countries'));
    }

    public function new_tenant_store(Request $request)
    {
        $request->validate([
            'name'=> 'required|string|max:191',
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'country'=> 'nullable',
            'city'=> 'nullable',
            'mobile'=> 'nullable',
            'state'=> 'nullable',
            'address'=> 'nullable',
            'image'=> 'nullable',
            'company'=> 'nullable',
            'username'=> 'required|unique:users',
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
            $event_data = ['id' =>  $user->id, 'title' =>  __('New user registered..!'), 'type' =>  'user_registration',];
            event(new TenantNotificationEvent($event_data));
        //Notification Event

        return response()->success(ResponseMessage::success(__('Tenant has been created successfully..!')));

    }

    public function edit_profile($id)
    {
        $user = User::find($id);
        $countries = \Modules\CountryManage\Entities\Country::select('id','name')->get();
        return view(self::BASE_PATH.'edit',compact('user','countries'));
    }

    public function update_edit_profile(Request $request)
    {
        $request->validate([
            'name'=> 'required|string|max:191',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users','email')->ignore($request->id)],
            'username' => ['required', 'string','max:255', Rule::unique('users','username')->ignore($request->id)],
            'country'=> 'nullable',
            'city'=> 'nullable',
            'mobile'=> 'nullable',
            'state'=> 'nullable',
            'address'=> 'nullable',
            'image'=> 'nullable',
            'company'=> 'nullable',
        ]);

        User::where('id',$request->id)->update([
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
        $tenants = Tenant::where('user_id',$user->id)->get();

        //media directory delete
        foreach ($tenants ?? [] as $tenant)
        {
            $path = 'assets/tenant/uploads/media-uploader/'.$tenant->id;

            if(\File::exists($path) && is_dir($path)){
                File::deleteDirectory($path);
            }

            $tenant_custom_font_path = 'assets/tenant/frontend/custom-fonts/'.$tenant->id.'/';
            if(\File::exists($tenant_custom_font_path) && is_dir($tenant_custom_font_path)){
                File::deleteDirectory($tenant_custom_font_path);
            }

            //dynamic assets delete
            if(file_exists('assets/tenant/frontend/themes/css/dynamic-styles/'.$tenant->id.'-style.css')){
                unlink('assets/tenant/frontend/themes/css/dynamic-styles/'.$tenant->id.'-style.css');
            }

            if(file_exists('assets/tenant/frontend/themes/js/dynamic-scripts/'.$tenant->id.'-script.js')){
                unlink('assets/tenant/frontend/themes/js/dynamic-scripts/'.$tenant->id.'-script.js');
            }
        }

        PaymentLogs::where('user_id',$user->id)->delete();
        PaymentLogHistory::where('user_id',$user->id)->delete();
        CustomDomain::where('user_id',$user->id)->delete();
        PackageHistory::where('user_id',$user->id)->delete();

        if(!empty($tenants)){
            foreach ($tenants as $tenant)
            {
                $tenant->domains()->delete();
                $tenant->delete();
            }
        }

        $user->delete();

        return response()->danger(ResponseMessage::delete(__('Tenant deleted successfully..!')));
    }

    public function update_change_password(Request $request)
    {
        $this->validate(
            $request,[
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


    public function send_mail(Request $request){

        $this->validate($request,[
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required',
        ]);

        $sub =  $request->subject;
        $msg = $request->message;

        try {
            Mail::to($request->email)->send(new BasicMail($msg,$sub));
        }catch (\Exception $ex){
            return response()->danger(ResponseMessage::delete($ex->getMessage()));
        }

        return response()->success(ResponseMessage::success(__('Mail Send Successfully..!')));
    }

    public function resend_verify_mail(Request $request){


        $subscriber_details = User::findOrFail($request->id);
        $token = $subscriber_details->email_verify_token ? $subscriber_details->email_verify_token  : Str::random(8);

        if (empty($subscriber_details->email_verify_token)){
            $subscriber_details->email_verify_token = $token;
            $subscriber_details->save();
        }
        $message = __('Verification Code: ').'<strong>'.$token.'</strong>'.'<br>'.__('Verify your email to get all news from '). get_static_option('site_'.get_default_language().'_title') . '<div class="btn-wrap"> <a class="anchor-btn" href="' . route('landlord.user.login') . '">' . __('Login') . '</a></div>';

        $msg = $message;
        $subject = __('verify your email');


        try {
            Mail::to($subscriber_details->email)->send(new BasicMail($msg,$subject));
        }catch (\Exception $ex){
            return response()->danger(ResponseMessage::delete($ex->getMessage()));
        }

        return response()->success(ResponseMessage::success(__('Email Verify Mail Send Successfully..!')));
    }

    public function tenant_activity_log()
    {
        $activities = Activity::with(['subject','causer'])->latest()->get();
        return view(self::BASE_PATH.'activity-log',compact('activities'));
    }

    public function tenant_activity_log_delete($id)
    {
        DB::table('tenant_activity_log')->where('id',$id)->delete();
        return response()->success(ResponseMessage::success(__('Logs cleared successfully..!')));
    }

    public function tenant_activity_log_all_delete()
    {
        DB::table('tenant_activity_log')->delete();
        return response()->success(ResponseMessage::success(__('All Logs cleared successfully..!')));
    }


    public function tenant_details($id)
    {
        $user = User::with('tenant_details','tenant_details.payment_log')->findOrFail($id);

        return view(self::BASE_PATH.'details',compact('user'));
    }

    public function tenant_domain_delete($tenant_id)
    {
        //old domain = same = tenant id //
        $tenant = Tenant::findOrFail($tenant_id);
        $user_id = $tenant->user_id;

        $tenant_custom_font_path = 'assets/tenant/frontend/custom-fonts/'.$tenant->id.'/';
        if(\File::exists($tenant_custom_font_path) && is_dir($tenant_custom_font_path)){
            File::deleteDirectory($tenant_custom_font_path);
        }


        //dynamic assets delete
        if(file_exists('assets/tenant/frontend/themes/css/dynamic-styles/'.$tenant->id.'-style.css')){
            unlink('assets/tenant/frontend/themes/css/dynamic-styles/'.$tenant->id.'-style.css');

        }

        if(file_exists('assets/tenant/frontend/themes/js/dynamic-scripts/'.$tenant->id.'-script.js')){
            unlink('assets/tenant/frontend/themes/js/dynamic-scripts/'.$tenant->id.'-script.js');
        }

        $path = 'assets/tenant/uploads/media-uploader/'.$tenant->id;
        CustomDomain::where([['old_domain', $tenant->id], ['custom_domain_status', '!=','connected']])
            ->orWhere([['custom_domain', $tenant->id], ['custom_domain_status', '==', 'connected']])->delete();

        PaymentLogs::where('tenant_id',$tenant->id)->delete();
        PaymentLogHistory::where('tenant_id',$tenant->id)->delete();
        PackageHistory::where('user_id',$user_id)->delete();


        if(!empty($tenant)){

             try{
                $tenant->domains()->delete();
                $tenant->delete();
             }catch(\Exception $ex){

                 $message = $ex->getMessage();

                 if(str_contains($message,'Access denied')){
                     return response()->danger(ResponseMessage::delete('Make sure your database user has permission to delete domain & database'));
                 }

                 if(str_contains($message,'drop database')){
                     return response()->danger(ResponseMessage::delete('Data deleted'));
                 }
             }


        }
        if(\File::exists($path) && is_dir($path)){
            File::deleteDirectory($path);
        }



        $check_tenant = Tenant::where('user_id', $user_id)->count();
        if ($check_tenant >! 0)
        {
            User::findOrFail($user_id)->update(['has_subdomain' => false]);
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

        public function assign_subscription(Request $request){

        $request->validate([
            'package' => 'required',
            'payment_status' => 'required',
        ]);

        //todo optimise code for assign subscription

        $user = User::findOrFail($request->subs_user_id);
        $package = PricePlan::findOrFail($request->subs_pack_id);

        if($package->type == 3){
            $request->validate([
                'custom_expire_date' => 'required',
            ],['custom_expire_date.required' => __('Please select custom package expire date')]);
        }




        //todo manage create tenant website

        $custom_expire_date = $request->custom_expire_date;

        $subdomain = $request->custom_subdomain != null ? $request->custom_subdomain : $request->subdomain;

         //checking eCommerce feature is available or not
              $request_theme_slug_or_default = $request->theme;

              $theme_code = '';

                $all_features = $package->plan_features ?? [];
                $check_feature_name = $all_features->pluck('feature_name')->toArray();

                if ($request_theme_slug_or_default == 'eCommerce' && !in_array('eCommerce',$check_feature_name)) {
                     return redirect()->back()->with(FlashMsg::item_delete(__('Please give ecommerce feature on price plan first for this theme')));
                }else{
                    $request_theme_slug_or_default =  $request_theme_slug_or_default;
                }
            //checking eCommerce feature is available or not

        $package_start_date = '';
        $package_expire_date =  '';
        if(!empty($package)){

            if($package->type == 0){ //monthly
                $package_start_date = Carbon::now()->format('d-m-Y h:i:s');
                $package_expire_date = Carbon::now()->addMonth(1)->format('d-m-Y h:i:s');

            }elseif ($package->type == 1){ //yearly
                $package_start_date = Carbon::now()->format('d-m-Y h:i:s');
                $package_expire_date = Carbon::now()->addYear(1)->format('d-m-Y h:i:s');

            }elseif ($package->type == 3){ //yearly
                $package_start_date = Carbon::now()->format('d-m-Y h:i:s');
                $package_expire_date = Carbon::parse($custom_expire_date)->format('d-m-Y h:i:s');
            }else{ //lifetime
                $package_start_date = Carbon::now()->format('d-m-Y h:i:s');
                $package_expire_date = null;
            }
        }




        $tenant = Tenant::find($subdomain);

        if (!empty($tenant))
        {
            $old_tenant_log = PaymentLogs::where(['user_id'=>$user->id, 'tenant_id' => $tenant->id ])->latest()->first();

            if (!empty($old_tenant_log->package_id) && !empty($old_tenant_log->user_id) && $old_tenant_log->user_id == $user->id)
            {

                if ($package_expire_date != null) {
                    $old_days_left = Carbon::now()->diff($old_tenant_log->expire_date);
                    $left_days = 0;

                    if ($old_days_left->invert == 0) {
                        $left_days = $old_days_left->days;
                    }

                    $renew_left_days = 0;
                    $renew_left_days = Carbon::parse($package_expire_date)->diffInDays();
                    $sum_days = $left_days + $renew_left_days;
                    $new_package_expire_date = Carbon::today()->addDays($sum_days)->format("d-m-Y h:i:s");
                } else {
                    $new_package_expire_date = null;
                }


                PaymentLogs::findOrFail($old_tenant_log->id)->update([
                    'custom_fields' =>  [],
                    'attachments' =>  [],
                    'email' => $old_tenant_log->email,
                    'name' => $old_tenant_log->name,
                    'package_name' => $package->title,
                    'package_price' => $package->price,
                    'package_gateway' => null,
                    'package_id' => $package->id,
                    'user_id' => $old_tenant_log->user_id,
                    'tenant_id' => $tenant->id,
                    'status' => $package->has_trial == 1 ? 'trial' : 'complete',
                    'payment_status' => $request->payment_status,
                    'renew_status' => is_null($old_tenant_log->renew_status) ? 1 : $old_tenant_log->renew_status + 1,
                    'is_renew' => 1,
                    'track' => Str::random(10) . Str::random(10),
                    'updated_at' => Carbon::now(),
                    'start_date' => $package_start_date,
                    'expire_date' => $new_package_expire_date,
                    'theme' => $old_tenant_log->theme,
                    'theme_code' => $old_tenant_log->theme_code,
                    'assign_status' => $old_tenant_log->assign_status
                ]);

                DB::table('tenants')->where('id', $tenant->id)->update([
                    'renew_status' => $renew_status = is_null($tenant->renew_status) ? 0 : $tenant->renew_status+1,
                    'is_renew' => $renew_status == 0 ? 0 : 1,
                    'start_date' => $package_start_date,
                    'expire_date' => $new_package_expire_date
                ]);

                $payment_details = PaymentLogs::findOrFail($old_tenant_log->id);

                LandlordPricePlanAndTenantCreate::store_payment_log_history($payment_details->id);

                //Notification Event
                    $event_data = ['id' =>  $payment_details->id, 'title' =>  __('Package subscription renewed by admin'), 'type' =>  'package_renew',];
                    event(new TenantNotificationEvent($event_data));
                //Notification Event
            }
        } else{


            $request->validate([
                'theme' => 'required',
                'custom_subdomain' =>'required'
            ]);


            $payment_log_id = PaymentLogs::create([
                'email' => $user->email,
                'name' => $user->name,
                'package_name' => $package->title,
                'package_price' => $package->price,
                'package_gateway' => null,
                'package_id' => $package->id,
                'user_id' => $user->id,
                'tenant_id' => $subdomain,
                'status' => 'complete',
                'is_renew' => 0,
                'payment_status' => $request->payment_status,
                'track' => Str::random(10) . Str::random(10),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'start_date' => $package_start_date,
                'expire_date' => $package_expire_date,
                'theme' => $request_theme_slug_or_default,
                'theme_code' => $theme_code,
                'assign_status' => 1
            ]);


            $payment_details = PaymentLogs::findOrFail($payment_log_id->id);

            LandlordPricePlanAndTenantCreate::store_payment_log_history($payment_details->id);

          try{

            event(new TenantRegisterEvent($user, $subdomain, $request_theme_slug_or_default));

            DB::table('tenants')->where('id', $payment_log_id->tenant_id)->update([
                'start_date' => $package_start_date,
                'expire_date' => $package_expire_date,
                'theme_slug' => $request_theme_slug_or_default,
            ]);

           //Event Notification
                $event_data = ['id' =>  $payment_details->id, 'title' =>  __('New subscription assigned by admin'), 'type' =>  'new_subscription',];
                event(new TenantNotificationEvent($event_data));
            //Event Notification


         }catch(\Exception $ex){

              $message = $ex->getMessage();
              if(strpos($message,'Access denied') == true){

                      $admin_mail_message = sprintf(__('Database Crating failed for user id %1$s , please checkout admin panel and generate database for this user trial from admin panel manually'),$payment_details->user_id);
                      $admin_mail_subject = sprintf(__('Database Crating failed on trial request for user id %1$s'),$payment_details->user_id);
                        //added in try catch block, because if any user did not configure smtp yet, but trying to create user website, it throwing 500error
                      try{
                          Mail::to(get_static_option('site_global_email'))->send(new BasicMail($admin_mail_message,$admin_mail_subject));
                      }catch(\Exception $e){
                          //
                      }

                     LandlordPricePlanAndTenantCreate::store_exception($payment_details->tenant_id,'domain create failed',$message,0);

                  return redirect()->back()->with(FlashMsg::item_delete(__('You have no permission to create database, we have created an issue, please go to website settings and manually generate this..!')));
                }

        }

            $raw_pass = get_static_option_central('landlord_default_tenant_admin_password_set') ?? '12345678';
            $credential_password = !empty(get_static_option_central('tenant_seeding_password_status')) ? \session()->get('random_password_for_tenant') : $raw_pass;
            $credential_email = $user->email;
            $credential_username = get_static_option_central('landlord_default_tenant_admin_username_set') ?? 'super_admin';

            $lang = get_user_lang();
            $user_dynamic_mail_sub = get_static_option('subscription_order_credential_mail_user_'.$lang.'_subject');
            $user_dynamic_mail_mess = get_static_option('subscription_order_credential_mail_user_'.$lang.'_message');

            try{
                //User Credential Mail
                if(!empty($user_dynamic_mail_sub) && !empty($user_dynamic_mail_mess)){
                    Mail::to($credential_email)->send(new BasicDynamicTemplateMail(SubscriptionEmailTemplate::SubscriptionCredentialMail($payment_details)));
                }else{
                    Mail::to($credential_email)->send(new TenantCredentialMail($credential_username, $credential_password));
                }

            }catch(\Exception $e){}
        }

        $order_mail = get_static_option('order_page_form_mail');
        $order_mail = !empty($order_mail) ? $order_mail : get_static_option('site_global_email');


        $lang = get_user_lang();
        $admin_dynamic_mail_sub = get_static_option('subscription_order_mail_admin_'.$lang.'_subject');
        $admin_dynamic_mail_mess = get_static_option('subscription_order_mail_admin_'.$lang.'_message');

        $user_dynamic_mail_sub = get_static_option('subscription_order_mail_user_'.$lang.'_subject');
        $user_dynamic_mail_mess = get_static_option('subscription_order_mail_user_'.$lang.'_message');


     $mail_notificatin_message = '';
        try {
            $all_fields = [];
            $all_attachment = [];

            //Admin Mail
            if(!empty($admin_dynamic_mail_sub) && !empty($admin_dynamic_mail_mess)){
                Mail::to($order_mail)->send(new BasicDynamicTemplateMail(SubscriptionEmailTemplate::SubscriptionAdminMail($payment_details)));
            }else{
                Mail::to($order_mail)->send(new PlaceOrder($all_fields, $all_attachment, $payment_details, "admin", 'regular'));
            }

            //User Mail
            if(!empty($user_dynamic_mail_sub) && !empty($user_dynamic_mail_mess)){
                Mail::to($payment_details->email)->send(new BasicDynamicTemplateMail(SubscriptionEmailTemplate::SubscriptionUserMail($payment_details)));
            }else{
                Mail::to($payment_details->email)->send(new PlaceOrder($all_fields, $all_attachment, $payment_details, 'user', 'regular'));
            }

        }catch (\Exception $e){

            $mail_notificatin_message = __('email notification send failed to user, regarding this website creation, please make sure your smtp is working properly, and user has a valid email adress.');
        }

            return response()->success(ResponseMessage::success(__('Subscription assigned for this user').' '.$mail_notificatin_message));
    }


    public function account_settings()
    {
        return view(self::BASE_PATH.'settings');
    }

    public function account_settings_update(Request $request)
    {
        $request->validate([
            'tenant_account_delete_notify_mail_days' => 'required',
            'account_remove_day_within_expiration' => 'required|alpha_num|min:1',
        ]);

        $limit_days = 15;

        if($request->account_remove_day_within_expiration >= $limit_days){
            return redirect()->back()->with(['type'=> 'danger', 'msg' => sprintf('You can not set remove account day avobe %d',$limit_days)]);
        }
        update_static_option('tenant_account_delete_notify_mail_days',json_encode($request->tenant_account_delete_notify_mail_days));
        update_static_option('account_remove_day_within_expiration',$request->account_remove_day_within_expiration);

        return response()->success(ResponseMessage::success());
    }

    public function tenant_cronjob_log()
    {
        $cronjobs = CronjobLog::orderByDesc('id')->get();
        return view(self::BASE_PATH.'cronjob-log',compact('cronjobs'));
    }

    public function tenant_cronjob_log_delete($id)
    {
        DB::table('cronjob_logs')->where('id',$id)->delete();
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
        $package = (object) [];
        if(!empty($package_id)){
            $package = PricePlan::find($package_id);

            $data_base_features = $package->plan_features?->pluck('feature_name')->toArray() ?? [];
            //Themes
            $json_all_themes = getAllThemeDataForAdmin();
            $themes = [];
            foreach ($json_all_themes as $theme){
                if(in_array('theme-'.$theme->slug,$data_base_features)){
                    $themes[] = $theme;
                }
            }
            //Themes

            $theme_view = view('landlord.frontend.user.dashboard.theme-string-blade-data',compact('themes'))->render();

            return response()->json([
                'theme_markup' =>$theme_view,
            ]);
        }
    }


    public  function update_website_instruction_status(Request $request)
    {
        $tenant_id = $request->id;
        $status = $request->instruction_status;

//        DB::table('tenants')->where('id',$tenant_id)->update([
//            'instruction_status' => !empty($status) ? 1 : 0
//        ]);

        Tenant::where("id",$tenant_id)->update([
            'instruction_status' => !empty($status) ? 1 : 0
        ]);

//        $tenant = Tenant::findOrFail($tenant_id);
//        $tenant->instruction_status = 0;
//        $tenant->save();

        return response()->success(ResponseMessage::success(__('Instruction Status Changed..!')));
    }




}
