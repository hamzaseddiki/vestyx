<?php

namespace App\Http\Controllers\Landlord\Frontend;

use App\Actions\Tenant\TenantCreateEventWithMail;
use App\Actions\Tenant\TenantTrialPaymentLog;
use App\Events\TenantNotificationEvent;
use App\Facades\EmailTemplate;
use App\Facades\GlobalLanguage;
use App\Helpers\EmailHelpers\VerifyUserMailSend;
use App\Helpers\Payment\DatabaseUpdateAndMailSend\LandlordPricePlanAndTenantCreate;
use App\Http\Controllers\Controller;
use App\Mail\AdminResetEmail;
use App\Mail\BasicDynamicTemplateMail;
use App\Mail\BasicMail;
use App\Mail\UserResetEmail;
use App\Models\Coupon;
use App\Models\CouponLog;
use App\Models\Newsletter;
use App\Models\Page;
use App\Models\PaymentLogs;
use App\Models\PricePlan;
use App\Models\Themes;
use App\Models\User;
use App\Traits\SeoDataConfig;
use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use PragmaRX\Google2FALaravel\Support\Authenticator;
use function view;

class LandlordFrontendController extends Controller
{
    use SEOToolsTrait, SeoDataConfig;

    private const BASE_VIEW_PATH = 'landlord.frontend.';


    public function loginUsingToken($token, Request $request){

        if(empty($token)){
            return redirect()->to(route('landlord.user.login'));
        }

        $user = null;
        if(!empty($request->user_id)){
            $user = User::find($request->user_id);
        }

        $hash_token = hash_hmac(
            'sha512',
            $user->username,
            $user->id
        );
        if(!hash_equals($hash_token,$token)){

            return redirect()->to(route('landlord.user.login'));
        }

        //login using super admin id
        if (Auth::guard('web')->loginUsingId($user->id)){
            return redirect()->to(route('landlord.user.home'));
        }
        //pic a random super admin account...

        return redirect()->to(route('landlord.user.login'));
        //redirect to admin panel home page
    }

    public function homepage()
    {
        $id = get_static_option('home_page');
        $page_post = Page::usingLocale(GlobalLanguage::user_lang_slug())->where('id', $id)->first();
        $this->setMetaDataInfo($page_post);
        return view(self::BASE_VIEW_PATH . 'frontend-home', compact('page_post'));
    }

    /* -------------------------
        SUBDOMAIN AVIALBILITY
    -------------------------- */
    public function subdomain_check(Request $request)
    {
        $this->validate($request, [
            'subdomain' => 'required|unique:tenants,id'
        ]);
        return response()->json('ok');
    }

    public function coupon_check(Request $request)
    {
        $validate_package_price = is_null($request->package_price) ? 'required' : 'nullable';

        $this->validate($request, [
            'package_id' => $validate_package_price,
            'coupon_code' => 'required'
        ],[
            'package_id.required' => __('The package selection is required')
        ]);

        $package_price = $request->package_price;
        $coupon_code = $request->coupon_code;

        if(!is_null($coupon_code)){

            $fetch_coupon = Coupon::where(['status' => 1, 'code'=>$coupon_code])->first();
            $auth_id = Auth::guard('web')->id();
            if(!empty($fetch_coupon)){
                $maximum_use_fetch = CouponLog::where(['coupon_id' => $fetch_coupon->id, 'user_id' =>$auth_id ])->count();
                if($fetch_coupon->expire_date <= now()){
                    return response()->json(['msg' => 'Coupon Expired', 'type' => 'danger','status' => 'expired']);
                }else{
                    if(!empty($maximum_use_fetch) && $maximum_use_fetch >= $fetch_coupon->max_use_qty){
                        return response()->json(['msg' => 'Coupon limit is over maximum access limit is ', 'type' => 'danger','status' => 'limit_over', 'limit' => $fetch_coupon->max_use_qty]);
                    }else{
                        $coupon_applied_price = get_amount_after_landlord_coupon_apply($package_price,$coupon_code);
                        return response()->json(['msg' => 'Coupon Applied', 'type' => 'success','status' => 'applied', 'price' => $coupon_applied_price ]);
                    }
                }
            }else{
                return response()->json(['msg' => 'Invalid Coupon', 'type' => 'danger','status' => 'invalid']);
            }
        }

    }

    /* -------------------------
        TENENT EMAIL VERIFY
    -------------------------- */
    public function verify_user_email()
    {
        if (empty(get_static_option('user_email_verify_status'))) {
            return redirect()->route('landlord.user.home');
        }

        if (Auth::guard('web')->user()->email_verified == 1) {
            return redirect()->route('landlord.user.home');
        }
        $this->setMetaDataInfo(null,[
            "title" => __('Email Verify')
        ]);

        return view('landlord.frontend.user.email-verify');
    }

    public function check_verify_user_email(Request $request)
    {
        $this->validate($request, [
            'verify_code' => 'required|string'
        ]);
        $user_info = User::where(['id' => Auth::guard('web')->id(), 'email_verify_token' => $request->verify_code])->first();
        if (is_null($user_info)) {
            return back()->with(['msg' => __('enter a valid verify code'), 'type' => 'danger']);
        }

        $user_info->email_verified = 1;
        $user_info->save();

        return redirect()->route('landlord.user.home');
    }

    public function resend_verify_user_email(Request $request)
    {
        $user_details = Auth::guard('web')->user();
        $dynamic_verify_mail_sub = get_static_option('user_email_verify_'.get_user_lang().'_subject');
        $dynamic_verify_mail_message = get_static_option('user_email_verify_'.get_user_lang().'_message');

        try {

            if(!empty($dynamic_verify_mail_sub) && !empty($dynamic_verify_mail_message)){

                Mail::to($user_details->email)->send(new BasicDynamicTemplateMail(EmailTemplate::userVerifyMail($user_details)));
            }else{
                VerifyUserMailSend::sendMail($user_details);
            }

        }catch (\Exception $e){

        }

        $this->setMetaDataInfo(null,[
            "title" => __('Email Verify')
        ]);

        return redirect()->route('landlord.user.email.verify')->with(['msg' => __('Verify mail send'), 'type' => 'success']);
    }

    public function dynamic_single_page($slug)
    {
        $page_post = Page::usingLocale(GlobalLanguage::user_lang_slug())->where('slug', $slug)->first();

        if(empty($page_post)){
            return view('errors.landlord-404');
        }

        $this->setMetaDataInfo($page_post);

        $price_page_slug = get_page_slug(get_static_option('pricing_plan'), 'pricing-plan');

        if(!empty($price_page_slug) && $slug == 'pricing-plan'){
            $page_post = Page::usingLocale(GlobalLanguage::user_lang_slug())->where('slug', $price_page_slug)->first();
            return view(self::BASE_VIEW_PATH . 'pages.dynamic-single')->with([
                'page_post' => $page_post
            ]);
        }


        return view(self::BASE_VIEW_PATH . 'pages.dynamic-single')->with([
            'page_post' => $page_post
        ]);
    }

    public function ajax_login(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|string',
            'password' => 'required|min:6'
        ], [
            'username.required' => __('Username required'),
            'password.required' => __('Password required'),
            'password.min' => __('Password length must be 6 characters')
        ]);
        if (Auth::guard('web')->attempt([$this->username() => $request->username, 'password' => $request->password], $request->get('remember'))) {

            activity()->log('Login');

            $auth_id = Auth::guard('web')->id();
            DB::table('tenant_activity_log')->where('causer_id',$auth_id)->update([
                'user_ip' => \request()->ip(),
                'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            ]);

            return response()->json([
                'msg' => __('Login Success Redirecting'),
                'type' => 'success',
                'status' => 'valid'
            ]);
        }
        return response()->json([
            'msg' => __('User name and password do not match'),
            'type' => 'danger',
            'status' => 'invalid'
        ]);
    }

    public function username()
    {
        $type = 'username';
        //check is email or username
        if (filter_var(\request()->username,FILTER_VALIDATE_EMAIL)){
            $type = 'email';
        }
        return $type;
    }


    public function lang_change(Request $request)
    {
        session()->put('lang', $request->lang);
        return redirect()->route('landlord.homepage');
    }


    public function order_payment_cancel($id)
    {
        $this->setMetaDataInfo(null,[
            "title" => __('Order Cacnel')
        ]);
        $order_details = PaymentLogs::find($id);
        return view('landlord.frontend.payment.payment-cancel')->with(['order_details' => $order_details]);
    }


    public function order_payment_cancel_static()
    {
        $this->setMetaDataInfo(null,[
            "title" => __('Order Cacnel')
        ]);
        return view('landlord.frontend.payment.payment-cancel-static');
    }

    public function view_plan($id, $trial = null)
    {
        $order_details = PricePlan::findOrFail($id);
        $data_base_features = $order_details->plan_features?->pluck('feature_name')->toArray() ?? [];
        $json_all_themes = getAllThemeDataForAdmin();


        $themes = [];
        foreach ($json_all_themes as $theme){
            if(in_array('theme-'.$theme->slug,$data_base_features)){
                $themes[] = $theme;
            }
        }

        $this->setMetaDataInfo(null,[
            "title" => __('View Plan')
        ]);

        return view('landlord.frontend.pages.package.view-plan')->with([
            'order_details' => $order_details,
            'themes' => $themes,
            'trial' => $trial != null ? true : false,
        ]);

    }

    public function plan_order($id)
    {
        if (empty($id)) {
            abort(404);
        }

        $order_details = PricePlan::findOrFail($id);
        $data_base_features = $order_details->plan_features?->pluck('feature_name')->toArray() ?? [];
        $json_all_themes = getAllThemeDataForAdmin();

        $themes = [];
        foreach ($json_all_themes as $theme){
            if(in_array('theme-'.$theme->slug,$data_base_features)){
                $themes[] = $theme;
            }
        }
        $this->setMetaDataInfo(null,[
            "title" => __('Order')
        ]);


        return view('landlord.frontend.pages.package.order-page')->with([
            'order_details' => $order_details,
            'themes' => $themes,

        ]);
    }

    public function order_confirm($id)
    {
        $order_details = PricePlan::where('id', $id)->first();
        $this->setMetaDataInfo(null,[
            "title" => __('Order Confirm')
        ]);
        return view('landlord.frontend.pages.package.order-page')->with(['order_details' => $order_details]);
    }


    public function order_payment_success($id)
    {
        $extract_id = substr($id, 6);
        $extract_id = substr($extract_id, 0, -6);

        if(empty($extract_id)){
            $extract_id = $id;
        }

        $payment_details = PaymentLogs::find($extract_id);

        $domain = \DB::table('domains')->where('tenant_id',$payment_details->tenant_id)->first();

        if (empty($extract_id)) {
            abort(404);
        }

        $this->setMetaDataInfo(null,[
            "title" => __('Payment Success')
        ]);
        return view('landlord.frontend.payment.payment-success', compact('payment_details','domain'));
    }

    public function logout_tenant_from_landlord()
    {
        Auth::guard('web')->logout();
        (new Authenticator(request()))->logout();
        return redirect()->back();
    }


// ========================================== LANDLORD HOME PAGE TENANT ROUTES ====================================


    public function showTenantLoginForm()
    {
        if (auth('web')->check()) {
            return redirect()->route('landlord.user.home');
        }
        $this->setMetaDataInfo(null,[
            "title" => __('Login')
        ]);
        return view('landlord.frontend.user.login');
    }

    public function showTenantRegistrationForm()
    {
        if (auth('web')->check()) {
            return redirect()->route('tenant.user.home');
        }
        $this->setMetaDataInfo(null,[
            "title" => __('Register')
        ]);
        return view('landlord.frontend.user.register');
    }

    protected function tenant_user_create(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'string', 'email', 'max:191', 'unique:users'],
            'username' => ['required', 'string', 'max:191', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'mobile' => ['required'],
        ]);

        $user_id = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'country' => $request['country'],
            'city' => $request['city'],
            'mobile' => $request['mobile'],
            'username' => $request['username'],
            'password' => Hash::make($request['password']),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ])->id;

        $user = User::findOrFail($user_id);

        //Notification Event
            $event_data = ['id' =>  $user->id, 'title' =>  __('New user registered..!'), 'type' =>  'user_registration',];
            event(new TenantNotificationEvent($event_data));
        //Notification Event

        Auth::guard('web')->login($user);

        return redirect()->route('landlord.user.home');
    }

    public function tenant_logout()
    {

        activity()->log('Logout');
        $auth_id = Auth::guard('web')->id();
        DB::table('tenant_activity_log')->where('causer_id',$auth_id)->update([
            'user_ip' => \request()->ip(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
        ]);

        Auth::guard('web')->logout();
        (new Authenticator(request()))->logout();

        $this->setMetaDataInfo(null,[
            "title" => __('Login')
        ]);
        return redirect()->route('landlord.user.login');
    }

    public function showUserForgetPasswordForm()
    {
        $this->setMetaDataInfo(null,[
            "title" => __('Forget Password')
        ]);
        return view('landlord.frontend.user.forget-password');
    }

    public function sendUserForgetPasswordMail(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|string:max:191'
        ]);
        $user_info = User::where('username', $request->username)->orWhere('email', $request->username)->first();
        if (!empty($user_info)) {
            $token_id = Str::random(30);
            $existing_token = DB::table('password_resets')->where('email', $user_info->email)->delete();
            if (empty($existing_token)) {
                DB::table('password_resets')->insert(['email' => $user_info->email, 'token' => $token_id]);
            }

            //dynamic mail template
            $dynamic_user_reset_mail_sub = get_static_option('user_reset_password_'.get_user_lang().'_subject');
            $dynamic_user_reset_mail_message = get_static_option('user_reset_password_'.get_user_lang().'_message');

            try{

                if(!empty($dynamic_user_reset_mail_sub) && !empty($dynamic_user_reset_mail_message)){


                    //fetch email tempalte content from admin panel
                    $message_body = get_static_option('user_reset_password_' . get_user_lang(). '_message');
                    $reset_url = '<a class="btn" href="' . route('landlord.user.reset.password', ['user' => $user_info->username, 'token' => $token_id]) . '"style="color:white;">' . __("Reset Password") . '</a>'."\n";
                    $message = str_replace(
                        [
                            '@username',
                            '@name',
                            '@reset_url'
                        ],
                        [
                            $user_info->username,
                            $user_info->name,
                            $reset_url
                        ], $message_body);

                    $data = [
                        'username' => $user_info->username,
                        'message' => $message
                    ];
                    Mail::to($user_info->email)->send(new UserResetEmail($data));
                }else{

                    $message = __('Here is you password reset link, If you did not request to reset your password just ignore this mail.') . ' <a class="btn" href="' . route('landlord.user.reset.password', ['user' => $user_info->username, 'token' => $token_id]) . '" style="color:white;">' . __('Click Reset Password') . '</a>';
                    $data = [
                        'username' => $user_info->username,
                        'message' => $message
                    ];
                    Mail::to($user_info->email)->send(new UserResetEmail($data));
                }


            }catch(\Exception $e){
                return redirect()->back()->with([
                    'type' => 'danger',
                    'msg' => $e->getMessage()
                ]);
            }

            return redirect()->back()->with([
                'msg' => __('Check Your Mail For Reset Password Link'),
                'type' => 'success'
            ]);
        }
        return redirect()->back()->with([
            'msg' => __('Your Username or Email Is Wrong!!!'),
            'type' => 'danger'
        ]);
    }

    public function showUserResetPasswordForm($username, $token)
    {
        $this->setMetaDataInfo(null,[
            "title" => __('Reset Password')
        ]);
        return view('landlord.frontend.user.reset-password')->with([
            'username' => $username,
            'token' => $token
        ]);
    }

    public function UserResetPassword(Request $request)
    {
        $this->validate($request, [
            'token' => 'required',
            'username' => 'required',
            'password' => 'required|string|min:8|confirmed'
        ]);
        $user_info = User::where('username', $request->username)->first();
        $user = User::findOrFail($user_info->id);
        $token_iinfo = DB::table('password_resets')->where(['email' => $user_info->email, 'token' => $request->token])->first();
        if (!empty($token_iinfo)) {
            $user->password = Hash::make($request->password);
            $user->save();
            return redirect()->route('landlord.user.login')->with(['msg' => __('Password Changed Successfully'), 'type' => 'success']);
        }
        return redirect()->back()->with(['msg' => __('Somethings Going Wrong! Please Try Again or Check Your Old Password'), 'type' => 'danger']);
    }


    public function newsletter_store(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string|email|max:191|unique:newsletters'
        ]);

        $verify_token = Str::random(32);
        $news = Newsletter::create([
            'email' => $request->email,
            'verified' => 0,
            'token' => $verify_token
        ]);

        //Notification Event
            $event_data = ['id' =>  $news->id, 'title' =>   __('New subscriber added'), 'type' =>  'newsletter_subscribed',];
            event(new TenantNotificationEvent($event_data));
        //Notification Event

        return response()->json([
            'msg' => __('Thanks for Subscribe Our Newsletter'),
            'type' => 'success'
        ]);
    }

    //landlord user trial account
    public function user_trial_account(Request $request)
    {
        $request->validate([
            'order_id' => 'required',
            'subdomain' => 'required|unique:tenants,id',
            'theme' => 'required',
        ],[
            'theme.required' => 'No theme is selected.'
        ]);

        $user_id = Auth::guard('web')->user()->id;
        $user = User::findOrFail($user_id);

        $plan = PricePlan::findOrFail($request->order_id);
        $subdomain = $request->subdomain;

        //checking eCommerce feature is available or not
            $all_features = $plan->plan_features ?? [];
            $check_feature_name = $all_features->pluck('feature_name')->toArray();

            $landlord_set_theme_code = get_static_option_central('landlord_default_theme_set');
            $theme = $request->theme;

            $theme_code = $request->theme_code;
            if (!in_array('eCommerce',$check_feature_name)) {
                if($landlord_set_theme_code == 'eCommerce' && $theme == 'eCommerce'){
                    return response()->json(['type' => 'danger', 'msg' => __('Please select a theme')]);
                }else{
                    $theme = $theme;
                    session()->put('theme',$theme);
                }
            }else{
                session()->put('theme',$theme);
            }
        //checking eCommerce feature is available or not


        $tenant_data = $user->tenant_details ?? [];
        $has_trial = false;
        if(!is_null($tenant_data)){
            foreach ($tenant_data as $tenant){
                if(optional($tenant->payment_log)->status == 'trial'){
                    $has_trial = true;
                }
            }
            if($has_trial == true){
                return response()->json([
                    'msg' => __('Your trial limit is over! Please purchase a plan to continue').'<br>'.'<small>'.__('You can make trial once only..!').'</small>',
                    'type' => 'danger'
                ]);
            }
        }



        try {
            TenantTrialPaymentLog::trial_payment_log($user,$plan,$subdomain,$theme,$theme_code);
        }catch (\Exception $e){

         }

        try{
            TenantCreateEventWithMail::tenant_create_event_with_credential_mail($user, $subdomain);
            session()->forget('random_password_for_tenant');

            $log = PaymentLogs::where('tenant_id',$subdomain)->first();
            DB::table('tenants')->where('id',$subdomain)->update([
                'start_date' => $log->start_date,
                'expire_date' => $log->expire_date,
                'theme_slug' => $theme,
            ]);

        }catch(\Exception $ex){
            $message = $ex->getMessage();

             $log = PaymentLogs::where('tenant_id',$subdomain)->first();

              $admin_mail_message = sprintf(__('Database Crating failed for user id %1$s , please checkout admin panel and generate database for this user trial from admin panel manually'),$log->user_id);
              $admin_mail_subject = sprintf(__('Database Crating failed on trial request for user id %1$s'),$log->user_id);
              Mail::to(get_static_option('site_global_email'))->send(new BasicMail($admin_mail_message,$admin_mail_subject));

            LandlordPricePlanAndTenantCreate::store_exception($subdomain,'domain failed on trial',$message,0);

            //Event Notification
                $event_data = ['id' => $log->id, 'title' => __('Database and domain create failed on trial'), 'type' => 'trial',];
                event(new TenantNotificationEvent($event_data));
            //Event Notification

            return response()->json(['msg' => __('Your trial website is not ready yet, we have notified to admin regarding your request, it is in admin approval stage..!, please try later..!'), 'type'=>'danger']);
        }


            $url = DB::table('domains')->where('tenant_id',$subdomain)->first()->domain;
            $url = tenant_url_with_protocol($url);
            $user->update(['has_subdomain' => 1]);


        //Notification Event
            $event_data = ['id' =>  $log->id, 'title' =>  __('Subscription Trial Added'), 'type' =>  'trial',];
            event(new TenantNotificationEvent($event_data));
        //Notification Event


        return response()->json([
            'url' => $url ?? url('/'),
            'type' => 'success'
        ]);
    }


    public function subscribe_page(Request $request)
    {
        abort_if(empty($request->token),404);

        Newsletter::where('token',$request->token)->update([
            'verified' => 1,
        ]);

        //Notification Event
            $newsletter = Newsletter::where('token',$request->token)->first();
            $event_data = ['id' =>  $newsletter->id, 'title' =>  __('New subscriber added'), 'type' =>  'newsletter_subscribed',];
            event(new TenantNotificationEvent($event_data));
        //Notification Event

        $this->setMetaDataInfo(null,[
            "title" => __('Subscribe')
        ]);

        return view('landlord.frontend.subscribe');
    }

    public function unsubscribe_page($id)
    {
        abort_if(empty($id),404);

       $newsletter =  Newsletter::find($id);
       $newsletter->verified = 0 ;
       $newsletter->save();

       //Notification Event
       $event_data = ['id' =>  $newsletter->id, 'title' =>  __('Newsletter unsubscribed'), 'type' =>  'newsletter_unsubscribed',];
       event(new TenantNotificationEvent($event_data));
       //Notification Event

       $this->setMetaDataInfo(null,[
            "title" => __('Subscribe')
        ]);

        return view('landlord.frontend.un-subscribe');
    }


}







