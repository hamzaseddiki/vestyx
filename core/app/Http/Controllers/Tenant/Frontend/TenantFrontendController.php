<?php

namespace App\Http\Controllers\Tenant\Frontend;

use App\Events\TenantNotificationEvent;
use App\Facades\GlobalLanguage;
use App\Helpers\EmailHelpers\VerifyUserMailSend;
use App\Helpers\ResponseMessage;
use App\Http\Controllers\Controller;
use App\Mail\AdminResetEmail;
use App\Mail\BasicMail;
use App\Mail\BasicMailTwo;
use App\Mail\UserResetEmail;
use App\Models\Admin;
use App\Models\Advertisement;
use App\Models\Newsletter;
use App\Models\Page;
use App\Models\PaymentLogs;
use App\Models\PricePlan;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\WeddingPaymentLog;
use App\Models\WeddingPricePlan;
use App\Models\Widgets;
use App\Traits\SeoDataConfig;
use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;
use Carbon\Carbon;
use Database\Seeders\Tenant\Addons\HomePageAddon;
use Database\Seeders\Tenant\ModuleData\Blog\BlogCategorySeed;
use Database\Seeders\Tenant\ModuleData\Blog\BlogSeed;
use Database\Seeders\Tenant\WidgetJsonConvertSeed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Modules\Attributes\Entities\Category;
use Modules\Attributes\Entities\Color;
use Modules\Attributes\Entities\Size;
use Modules\Blog\Entities\Blog;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductTag;

class TenantFrontendController extends Controller
{
    use SEOToolsTrait, SeoDataConfig;

    private const BASE_VIEW_PATH = 'tenant.frontend.';

    /* login using token */

    public function loginAsSuperAdminUsingToken($token){

        if(empty($token)){
            return redirect()->to(route('tenant.admin.login'));
        }

        $hash_token = hash_hmac(
            'sha512',
            tenant()->user?->username.'_'.tenant()->id,
            tenant()->unique_key
        );
        if(!hash_equals($hash_token,$token)){
            return redirect()->to(route('tenant.admin.login'));
        }


        $get_random_super_admin = DB::table('model_has_roles')->where('role_id',1)->inRandomOrder()->first();
        //login using super admin id
            if (Auth::guard('admin')->loginUsingId($get_random_super_admin->model_id)){
                return redirect()->to(route('tenant.admin.dashboard'));
            }
        //pic a random super admin account...

        return redirect()->to(route('tenant.admin.login'));
        //redirect to admin panel home page
    }

    public function homepage(){
        $id = get_static_option('home_page');
        $page_post = Page::usingLocale(GlobalLanguage::user_lang_slug() ?? 'en')->where('id', $id)->first();
        $this->setMetaDataInfo($page_post);

        return view(self::BASE_VIEW_PATH.'frontend-home')->with([
            'page_post' => $page_post
        ]);
    }

    public function dynamic_single_page ($slug){

        $page_post = Page::usingLocale(GlobalLanguage::default_slug())->where('slug', $slug)->first();

        if(empty($page_post)){
           return view('errors.tenant-404');
        }

        $shop_page_slug = get_page_slug(get_static_option('shop_page'), 'shop_page');

        if ($slug === $shop_page_slug) {
            if (tenant()) {
                $product_object = Product::where('status_id', 1)->latest()->paginate(12);
                $categories = Category::whereHas('product_categories')
                    ->select('id', 'name', 'slug')
                    ->withCount(['product_categories' => function($q) {
                        $q->whereHas('product', function($q) {
                            $q->where('status_id', 1);
                            $q->whereNull('deleted_at');
                        });
                    }])
                    ->get();

                $sizes = Size::whereHas('product_sizes')->select('id', 'name', 'size_code', 'slug')->get();
                $colors = Color::select('id', 'name', 'color_code', 'slug')->get();
                $tags = ProductTag::select('tag_name')->distinct()->get();

                $create_arr = request()->all();
                $create_url = http_build_query($create_arr);

                $product_object->url(route('tenant.shop') . '?' . $create_url);
                $product_object->url(route('tenant.shop') . '?' . $create_url);

                $links = $product_object->getUrlRange(1, $product_object->lastPage());
                $current_page = $product_object->currentPage();

                $products = $product_object->items();

                return view('product::frontend.shop.all-products')->with([
                    'page_post' => $page_post,
                    'products' => $products,
                    'links' => $links,
                    'current_page' => $current_page,
                    'pagination' => $product_object->withQueryString(),
                    'categories' => $categories,
                    'sizes' => $sizes,
                    'colors' => $colors,
                    'tags' => $tags
                ]);
            }
        }

        $donation_page_slug = get_page_slug(get_static_option('donation_page'), 'donation');
        if(!empty($donation_page_slug) && $slug == 'donation' ) {
            $page_post = Page::usingLocale(GlobalLanguage::default_slug())->where('slug', $donation_page_slug)->first();
            return view(self::BASE_VIEW_PATH . 'pages.dynamic-single')->with([
                'page_post' => $page_post,
            ]);
        }

        $event_page_slug = get_page_slug(get_static_option('event_page'), 'event');
        if(!empty($event_page_slug) && $slug == 'event' ) {
            $page_post = Page::usingLocale(GlobalLanguage::default_slug())->where('slug', $event_page_slug)->first();
            return view(self::BASE_VIEW_PATH . 'pages.dynamic-single')->with([
                'page_post' => $page_post,
            ]);
        }

        $job_page_slug = get_page_slug(get_static_option('job_page'), 'job');
        if(!empty($job_page_slug) && $slug == 'job' ) {
            $page_post = Page::usingLocale(GlobalLanguage::default_slug())->where('slug', $job_page_slug)->first();
            return view(self::BASE_VIEW_PATH . 'pages.dynamic-single')->with([
                'page_post' => $page_post,
            ]);
        }

        $knowledgebase_page_slug = get_page_slug(get_static_option('knowledgebase_page'), 'knowledgebase');
        if(!empty($knowledgebase_page_slug) && $slug == 'knowledgebase' ) {
            $page_post = Page::usingLocale(GlobalLanguage::default_slug())->where('slug', $knowledgebase_page_slug)->first();
            return view(self::BASE_VIEW_PATH . 'pages.dynamic-single')->with([
                'page_post' => $page_post,
            ]);
        }

        $this->setMetaDataInfo($page_post);


        return view(self::BASE_VIEW_PATH.'pages.dynamic-single')->with([
            'page_post' => $page_post,
        ]);
    }

    public function subscribe_newsletter(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|unique:newsletters'
        ]);

        try {
            Newsletter::create([
                'email' => $request->email,
                'verified' => 0,
            ]);
        }catch (\Exception $e){
            return response()->json([
                'msg' => __('Something went wrong'),
                'type' => 'error'
            ]);
        }

            $msg= __('You have new newsletter subscriber from'). get_static_option('site_'.get_user_lang().'_title') . '<div class="btn-wrap">
          </div>';

            $message = $msg;
            $subject =  __('Subscriber Newsletter');

        //send verify mail to newsletter subscriber
        try {
            Mail::to($request->email)->send(new BasicMail($message,$subject));
        }catch (\Exception $e){
            return response()->json([
                'msg' => __('Something went wrong'),
                'type' => 'error'
            ]);
        }

        return response()->json([
            'msg' => __('Thanks for Subscribe Our Newsletter'),
            'type' => 'success'
        ]);
    }

    public function showTenantLoginForm()
    {
        if (auth('web')->check()){
            return redirect()->route('tenant.user.home');
        }
        $this->setMetaDataInfo(null,[
            "title" => __('Login')
        ]);
        return view('tenant.frontend.user.login');
    }


    public function ajax_login(Request $request)
    {
        $this->validate($request, [
            'username' => 'required|string',
            'password' => 'required|min:6'
        ], [
            'username.required'   => __('Username required'),
            'password.required' => __('Password required'),
            'password.min' => __('Password length must be 6 characters')
        ]);
        if (Auth::guard('web')->attempt(['username' => $request->username, 'password' => $request->password], $request->get('remember'))) {
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

    public function showTenantRegistrationForm()
    {
        if (auth('web')->check()){
            return redirect()->route('tenant.user.home');
        }
        $this->setMetaDataInfo(null,[
            "title" => __('Register')
        ]);
        return view('tenant.frontend.user.register');
    }

    protected function tenant_user_create(Request $request)
    {
        $request ->validate([
            'name' => ['required', 'string', 'max:191'],
            'email' => ['required', 'string', 'email', 'max:191', 'unique:users'],
            'username' => ['required', 'string', 'max:191', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        DB::table('users')->insert([
            'name' => $request['name'],
            'email' => $request['email'],
            'country' => $request['country'],
            'city' => $request['city'],
            'username' => $request['username'],
            'password' => Hash::make($request['password']),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $email = get_static_option('site_global_email');

        try {

            $subject = __('New user registration');
            $message_body = __('New user registered : '). $request['name'];
            Mail::to($email)->send(new BasicMail($subject,$message_body));

        }catch (\Exception $e){
            //handle error
        }

        return redirect()->route('tenant.user.home');
    }

    public function tenant_logout()
    {
        Auth::guard('web')->logout();
        return redirect()->route('tenant.user.login');
    }

    public function showUserForgetPasswordForm()
    {
        $this->setMetaDataInfo(null,[
            "title" => __('Forget Password')
        ]);
        return view('tenant.frontend.user.forget-password');
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


            //fetch email tempalte content from admin panel
            $message_body = get_static_option('user_reset_password_' . get_user_lang(). '_message');
            $reset_url = '<a class="btn" href="' . route('tenant.user.reset.password', ['user' => $user_info->username, 'token' => $token_id]) . '"style="color:white;">' . __("Reset Password") . '</a>'."\n";
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


            try{
                Mail::to($user_info->email)->send(new UserResetEmail($data));
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
        return view('tenant.frontend.user.reset-password')->with([
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
            return redirect()->route('tenant.user.login')->with(['msg' => __('Password Changed Successfully'), 'type' => 'success']);
        }
        return redirect()->back()->with(['msg' => __('Somethings Going Wrong! Please Try Again or Check Your Old Password'), 'type' => 'danger']);
    }

    public function lang_change(Request $request)
    {
        session()->put('lang', $request->lang);
        return redirect()->route('tenant.frontend.homepage');
    }


    public function order_payment_cancel($id)
    {
        $order_details = PaymentLogs::find($id);
        return view('tenant.frontend.payment.payment-cancel')->with(['order_details' => $order_details]);
    }
    public function order_payment_cancel_static()
    {
        $this->setMetaDataInfo(null,[
            "title" => __('Payment Cancel')
        ]);
        return view('tenant.frontend.payment.payment-cancel-static');
    }


    public function order_payment_success($id)
    {
        $extract_id = substr($id, 6);
        $extract_id =  substr($extract_id, 0, -6);

        $payment_details = '';
          if(!empty($extract_id)){
             $payment_details = PaymentLogs::find($extract_id);
           }
        $this->setMetaDataInfo(null,[
            "title" => __('Payment Success')
        ]);
        return view('tenant.frontend.payment.payment-success',compact('payment_details'));

    }

    /* -------------------------
       USER EMAIL VERIFY
   -------------------------- */
    public function verify_user_email(){

        VerifyUserMailSend::sendMail(Auth::guard('web')->user());
        $this->setMetaDataInfo(null,[
            "title" => __('Email Verify')
        ]);
        return view('tenant.frontend.user.email-verify');
    }
    public function check_verify_user_email(Request $request){

        $this->validate($request,[
            'verify_code' => 'required|string'
        ]);
        $user_info = User::where(['id' => Auth::guard('web')->id(),'email_verify_token' => $request->verify_code])->first();

        if (is_null($user_info)){
            return back()->with(['msg' => __('enter a valid verify code'),'type' => 'danger']);
        }

        $user_info->email_verified = 1;
        $user_info->save();

       return redirect()->route('tenant.user.home');

    }

    public function resend_verify_user_email(Request $request){
        VerifyUserMailSend::sendMail(Auth::guard('web')->user());
        $this->setMetaDataInfo(null,[
            "title" => __('Resend Email Verify')
        ]);
        return redirect()->route('tenant.user.email.verify')->with(['msg' =>__('Verify mail send'),'type' =>'success']);
    }


    public function expired_package_redirection()
    {
        $current_tenant_payment_data = tenant()->expire_date;
        $diff = Carbon::parse($current_tenant_payment_data)->greaterThan(Carbon::today());
        if(tenant() && $diff) {
            return redirect()->route('tenant.frontend.homepage');
        }
        $this->setMetaDataInfo(null,[
            "title" => __('Expired')
        ]);
        return view('tenant.frontend.pages.expired-page');
    }



 /* -------------------------
    TENANT ADMIN EMAIL VERIFY
-------------------------- */
    public function verify_admin_email(){
        $this->setMetaDataInfo(null,[
            "title" => __('Email Verify')
        ]);
        return view('landlord.auth.verify');
    }

    public function check_verify_admin_email(Request $request){

        $this->validate($request,[
            'verify_code' => 'required|string'
        ]);

        $user_data = tenant()->user()->first();

        if (is_null($user_data)){
            return back()->with(['msg' => __('enter a valid verify code'),'type' => 'danger']);
        }

        $user_data->email_verified = 1;
        $user_data->save();

        return redirect()->route('tenant.admin.dashboard');
    }

    public function resend_admin_verify_user_email(Request $request){

        VerifyUserMailSend::sendMail_tenant_admin(Auth::guard('admin')->user());
        $this->setMetaDataInfo(null,[
            "title" => __('Email Verify')
        ]);
        return redirect()->route('tenant.admin.email.verify')->with(['msg' =>__('Verify mail send'),'type' =>'success']);
    }

    public function faq_mail_send(Request $request)
    {
        $this->validate($request,[
            'email' => 'required|string',
            'message' => 'required|string'
        ]);


        $email = get_static_option('tenant_site_global_email');
        try {
            $subject = __('Faq quistion mail from : ') . $request->email;
            $message_body = '<span class="user-registration">'.$request->message.'</span>';
            Mail::to($email)->send(new BasicMailTwo($message_body,$subject,$request->email));

        }catch (\Exception $e){
            //handle error
        }

        return response()->json(['type'=>'success','msg' => 'Mail Send Successfully..!']);
    }


    public function query_submit(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'subject' => 'required|string',
            'message' => 'required',
        ]);

        $message = $request->message;
        $subject =   $request->subject;;
        $tenant_global_email = get_static_option('tenant_site_global_email');

        try {
            Mail::to($tenant_global_email)->send(new BasicMail($message,$subject));
        }catch (\Exception $e){
            return redirect()->back()->with(ResponseMessage::delete($e->getMessage()));
        }

        return response()->json([
            'msg' => __('Thanks for your query..!'),
            'type' => 'success'
        ]);
    }


    public function newsletter_subscribe(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
        ]);

        $message = __('You have a new subscriber');
        $subject = __('Subscribed Newsletter ');
        $tenant_global_email = get_static_option('tenant_site_global_email');

        try {
            Mail::to($tenant_global_email)->send(new BasicMail($message,$subject));
        }catch (\Exception $e){
            return redirect()->back()->with(ResponseMessage::delete($e->getMessage()));
        }

        return response()->json([
            'msg' => __('Thanks for your subscription..!'),
            'type' => 'success'
        ]);
    }


    public function home_advertisement_click_store(Request $request)
    {
        Advertisement::where('id',$request->id)->increment('click');
        return response()->json('success');
    }

    public function home_advertisement_impression_store(Request $request)
    {
        Advertisement::where('id',$request->id)->increment('impression');
        return response()->json('success');
    }

    public function news_by_category_ajax(Request $request)
    {
        $all_news = Blog::where('category_id',$request->category_id)->take(6)->get();

        $markup = '';
        foreach ($all_news as $data){

            $route =  route('tenant.frontend.blog.single',$data->slug);
            $image = render_image_markup_by_attachment_id($data->image);
            $date = date('d M Y',strtotime($data->created_at));
            $title = $data->title;

            $markup.= <<<ITEM
              <div class="newspaper_banner__news">
                    <div class="newspaper_banner__news__flex">
                        <div class="newspaper_popular__news__thumb">
                            <a href="{$route}">
                               {$image}
                            </a>
                        </div>
                        <div class="newspaper_banner__news__contents">
                            <div class="newspaper_banner__news__date">
                                <a href="{$route}" class="newspaper_banner__news__date__item"><i class="las la-clock"></i> <span>{$date}</span></a>
                            </div>
                            <h5 class="newspaper_banner__news__title mt-1"><a href="{$route}">{$title}</a></h5>
                        </div>
                    </div>
                </div>
ITEM;

        }

        return response()->json($markup);
    }


    public function construction_testimonial_ajax(Request $request)
    {
        $testimonial = Testimonial::find($request->id);

        $markup = '';

            $image = render_image_markup_by_attachment_id($testimonial->image);
            $name = $testimonial->name;
            $designation = $testimonial->designation;
            $description =  \Illuminate\Support\Str::words($testimonial->description,30);

    $markup.= <<<ITEM
             <div class="construction__singleTestimonial hoverTab_item active center-text">
                <div class="construction__singleTestimonial__thumb">
                    {$image}
                    <span class="construction__singleTestimonial__thumb__quote"><i class="fa-solid fa-quote-left"></i></span>
                </div>
                <div class="construction__singleTestimonial__contents mt-5">
                    <div class="construction__singleTestimonial__contents__details">
                        <h4 class="construction__singleTestimonial__contents__title">{$name}</h4>
                        <span class="construction__singleTestimonial__contents__subtitle mt-1">{$designation}</span>
                        <div class="construction__singleTestimonial__contents__star mt-2">
                            <span><i class="fa-solid fa-star"></i></span>
                            <span><i class="fa-solid fa-star"></i></span>
                            <span><i class="fa-solid fa-star"></i></span>
                            <span><i class="fa-solid fa-star"></i></span>
                            <span><i class="fa-solid fa-star"></i></span>
                        </div>
                    </div>
                    <p class="construction__singleTestimonial__contents__para mt-4 mt-lg-5">{$description}</p>
                </div>
            </div>
ITEM;

        return response()->json($markup);
    }


    public function home_wedding_contact_store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
            'email' => 'required',
            'message' => 'required',
        ]);

        $msg = __('Message from'). ' : '. $request->name . '<br>';
        $msg.= __('Message'). ' : '. $request->message;

        $subject =  __('Contact Message ') . ' from' .get_static_option('site_'.get_user_lang().'_title');

        //send verify mail to newsletter subscriber
        try {
            Mail::to($request->email)->send(new BasicMail($msg,$subject));
        }catch (\Exception $e){
            return redirect()->back()->with(ResponseMessage::delete($e->getMessage()));
        }

        return response()->json([
            'msg' => __('Thanks for Contact Us'),
            'type' => 'success'
        ]);
    }


  // ============== WEDDING FUNCTIONS ============
    public function wedding_price_plan_order($id)
    {
        $order_details = WeddingPricePlan::findOrFail($id);
        $this->setMetaDataInfo(null,[
            "title" => __('Order')
        ]);
        return themeView('wedding-package.order-page',compact('order_details'));
    }

    public function wedding_order_payment_success($id)
    {
        $extract_id = substr($id, 6);
        $extract_id =  substr($extract_id, 0, -6);

        $payment_details = [];
        $order_details = [];
        if(!empty($extract_id)){
            $payment_details = WeddingPaymentLog::find($extract_id);
            $order_details = WeddingPricePlan::find($payment_details->package_id);
        }
        $this->setMetaDataInfo(null,[
            "title" => __('Success')
        ]);
        return themeView('wedding-package.payment-success',compact('payment_details','order_details'));
    }

    public function wedding_cancel_order()
    {
        //todo set seo meta data
$this->setMetaDataInfo(null,[
    "title" => __('Cancel')
]);
        return themeView('wedding-package.payment-cancel-static');
    }

    // ============== WEDDING FUNCTIONS ============

    public function tenant_alert_page()
    {
        return view('tenant.frontend.pages.alert-page');
    }

    public function subscribe_page(Request $request)
    {
        abort_if(empty($request->token),404);

        Newsletter::where('token',$request->token)->update([
            'verified' => 1,
        ]);

        //todo set seo meta data
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
        //todo set seo meta data
        $this->setMetaDataInfo(null,[
            "title" => __('Unsubscribe')
        ]);
        return view('landlord.frontend.un-subscribe');
    }

}
