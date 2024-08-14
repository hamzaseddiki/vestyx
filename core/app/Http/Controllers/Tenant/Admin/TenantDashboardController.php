<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Brand;
use App\Models\Language;
use App\Models\Page;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Modules\Blog\Entities\Blog;
use Modules\Campaign\Entities\Campaign;
use Modules\Donation\Entities\DonationPaymentLog;
use Modules\Event\Entities\Event;
use Modules\Event\Entities\EventPaymentLog;
use Modules\Job\Entities\Job;
use Modules\Job\Entities\JobPaymentLog;
use Modules\Knowledgebase\Entities\Knowledgebase;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductOrder;
use Modules\Service\Entities\Service;
use function __;
use function auth;
use function response;
use function view;


class TenantDashboardController extends Controller
{
    const BASE_PATH = 'tenant.admin.';

    public function redirect_to_tenant_admin_panel(){
        $user_details = Auth::guard('web')->user();
        return redirect()->to(get_tenant_website_url($user_details).'/admin-home');
    }

    public function dashboard(){

        //Checking ecommerce status data exists if not then store
        $this->check_store_ecommerce_status_table_and_data();

        $total_admin = Admin::count();
        $total_user= User::count();
        $all_blogs = Blog::count();
        $total_services = Service::count();;
        $total_language = Language::count();
        $total_brand = Brand::all()->count();
        $total_page = Page::all()->count();
        $current_package = optional(tenant()->payment_log())->first();

        $total_product = Product::all()->count();
        $total_campaign = Campaign::all()->count();
        $total_event = Event::all()->count();
        $total_job = Job::all()->count();
        $total_article = Knowledgebase::all()->count();

        $recent_product_order_logs = ProductOrder::orderByDesc('id')->take(5)->get();
        $recent_donation_logs = DonationPaymentLog::orderByDesc('id')->take(5)->get();
        $recent_event_logs = EventPaymentLog::orderByDesc('id')->take(5)->get();
        $recent_job_logs = JobPaymentLog::orderByDesc('id')->take(5)->get();

        return view(self::BASE_PATH.'admin-home',compact('total_admin','total_user','all_blogs',
                    'total_services','total_language','total_brand','current_package','total_product','total_campaign',
                    'total_event','total_job','total_article','total_page','recent_product_order_logs','recent_donation_logs',
                    'recent_event_logs','recent_job_logs'));
    }

    /* work later */
    public function verify_user_email(){
        return view('landlord.frontend.auth.login');
    }
    public function change_password(){
        return view(self::BASE_PATH.'profile.change-password');
    }
    public function edit_profile(){
        return view(self::BASE_PATH.'profile.edit-profile');
    }
    public function update_change_password(Request $request){
        $this->validate($request,[
           'password' => 'required|confirmed|min:8'
        ]);

        //
        tenant()->user()->name = 'test';
            tenant()->user()->save();

            Http::withToken(tenant()->user()->plain_text_token)->post('',[]);

        User::find(auth('web')->id())->update(['password'=> Hash::make($request->password)]);
        Auth::guard('web')->logout();
        return response()->success(__('Password Change Success'));
    }
    public function update_edit_profile(Request $request){
        $this->validate($request,[
           'name' => 'required|string',
           'email' => 'required|email',
           'mobile' => 'nullable|numeric',
           'company' => 'nullable|string',
           'city' => 'nullable|string',
           'state' => 'nullable|string',
           'address' => 'nullable|string',
           'country' => 'nullable|string',
           'image' => 'nullable|integer',
        ]);

        User::find(auth('web')->id())->update([
            'name' => $request->name,
            'email' => $request->email ,
            'mobile' => $request->mobile ,
            'company' => $request->company ,
            'city' => $request->city ,
            'state' => $request->state ,
            'address' => $request->address ,
            'country' => $request->country ,
            'image' => $request->image ,
        ]);

        return response()->success(__('Settings Saved'));
    }

    public function check_store_ecommerce_status_table_and_data()
    {
        if(Schema::hasTable('statuses')){
            if(Status::count() < 1){
                DB::table('statuses')->insert(['name'=>'active']);
                DB::table('statuses')->insert(['name'=>'inactive']);
            }
        }
    }

}
