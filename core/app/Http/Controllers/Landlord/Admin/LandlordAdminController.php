<?php

namespace App\Http\Controllers\Landlord\Admin;

use App\Facades\GlobalLanguage;
use App\Helpers\ResponseMessage;
use App\Helpers\TenantHelper\TenantGenerateHelper;
use App\Helpers\TenantHelper\TenantHelpers;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Brand;
use App\Models\PaymentLogs;
use App\Models\PricePlan;
use App\Models\Tenant;
use App\Models\Testimonial;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use function view;

class LandlordAdminController extends Controller
{
    private const BASE_VIEW_PATH = 'landlord.admin.';

    public function dashboard(){


        //check trial,,

        $total_admin= Admin::count();
        $total_user= User::count();
        $all_blogs = 0;//Blog::count() ?? 0;
        $total_price_plan = PricePlan::count();
        $total_brand = Brand::all()->count();
        $total_testimonial = Testimonial::all()->count();
        $recent_order_logs = PaymentLogs::orderBy('id','desc')->take(5)->get();

        return view(self::BASE_VIEW_PATH.'admin-home',compact('total_admin','total_user','all_blogs','total_brand','total_price_plan','total_testimonial','recent_order_logs'));
    }

    public  function health(){

        $all_user = Admin::all()->except(Auth::id());
//        return view(self::BASE_VIEW_PATH.'health',compact($all_user));
        return view(self::BASE_VIEW_PATH.'health')->with(['all_user' => $all_user]);
    }

    public function change_password(){
        return view(self::BASE_VIEW_PATH.'auth.change-password');
    }
    public function edit_profile(){
        return view(self::BASE_VIEW_PATH.'auth.edit-profile');
    }
    public function update_change_password(Request $request){
        $this->validate($request,[
            'password' => 'required|confirmed|min:8'
        ]);

        Admin::find(auth('admin')->id())->update(['password'=> Hash::make($request->password)]);
        //store this data in landlord database
        Auth::guard('admin')->logout();
        return response()->success(__('Password Change Success'));
    }
    public function update_edit_profile(Request $request){
        $this->validate($request,[
            'name' => 'required|string',
            'email' => 'required|email|unique:admins,email,'.auth('admin')->id(),
            'mobile' => 'nullable|numeric',
            'image' => 'nullable|integer',
        ]);

        Admin::find(auth('admin')->id())->update([
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile ,
            'image' => $request->image ,
        ]);

        //store this data in landlord database
        return response()->success(__('Settings Saved'));
    }

    public function topbar_settings()
    {
        return view('landlord.admin.topbar-settings');
    }

    public function update_topbar_settings(Request $request)
    {
        $request->validate([
            'topbar_phone'=>'nullable',
            'topbar_email'=>'nullable',
            'topbar_twitter_url'=>'nullable',
            'topbar_linkedin_url'=>'nullable',
            'topbar_facebook_url'=>'nullable',
            'topbar_instagram_url'=>'nullable',
            'landlord_frontend_language_show_hide'=>'nullable',
        ]);

        $data = [
            'topbar_phone',
            'topbar_email',
            'topbar_twitter_url',
            'topbar_linkedin_url',
            'topbar_facebook_url',
            'topbar_instagram_url',
            'topbar_youtube_url',
            'landlord_frontend_language_show_hide',
            'landlord_frontend_topbar_show_hide',

            'landlord_frontend_contact_info_show_hide',
            'landlord_frontend_social_info_show_hide',

            'tenant_login_show_hide',
            'tenant_register_show_hide',
        ];

        foreach ($data as $item)
        {
            update_static_option($item, $request->$item);
        }

        return response()->success(__('Settings Saved'));
    }

    public function get_chart_data_month(Request $request){
        /* -------------------------------------
            TOTAL ORDER BY MONTH CHART DATA
        ------------------------------------- */
        $all_donation_by_month = PaymentLogs::select('package_price','created_at')->where(['payment_status' => 'complete'])
            ->whereYear('created_at',date('Y'))
            ->get()
            ->groupBy(function ($query){
                return Carbon::parse($query->created_at)->format('F');
            })->toArray();
        $chart_labels = [];
        $chart_data= [];
        foreach ($all_donation_by_month as $month => $amount){
            $chart_labels[] = $month;
            $chart_data[] =  array_sum(array_column($amount,'package_price'));
        }
        return response()->json( [
            'labels' => $chart_labels,
            'data' => $chart_data
        ]);
    }

    public function get_chart_by_date_data(Request $request){
        /* -----------------------------------------------------
           TOTAL ORDER BY Per Day In Current month CHART DATA
       -------------------------------------------------------- */
        $all_donation_by_month = PaymentLogs::select('package_price','created_at')->where(['payment_status' => 'complete'])
            // ->whereMonth('created_at',date('m'))
            ->whereDate('created_at', '>', Carbon::now()->subDays(30))
            ->get()
            ->groupBy(function ($query){
                return Carbon::parse($query->created_at)->format('D, d F Y');
            })->toArray();
        $chart_labels = [];
        $chart_data= [];
        foreach ($all_donation_by_month as $month => $amount){
            $chart_labels[] = $month;
            $chart_data[] =  array_sum(array_column($amount,'package_price'));
        }

        return response()->json( [
            'labels' => $chart_labels,
            'data' => $chart_data
        ]);
    }

    public function login_register_settings()
    {
        return view('landlord.admin.login-register-settings');
    }

    public function upadate_login_register_settings(Request $request)
    {
        foreach (GlobalLanguage::all_languages() as $lang){
            $fields = [
                'landlord_user_login_'.$lang->slug.'_title'  => 'nullable|string',
                'landlord_user_register_'.$lang->slug.'_title' => 'nullable|string',

                'landlord_user_register_feature_'.$lang->slug.'_title_one' => 'nullable|string',
                'landlord_user_register_feature_'.$lang->slug.'_description_one' => 'nullable|string',
                'landlord_user_register_feature_'.$lang->slug.'_title_two' => 'nullable|string',
                'landlord_user_register_feature_'.$lang->slug.'_description_two' => 'nullable|string',
                'landlord_user_register_feature_'.$lang->slug.'_title_three' => 'nullable|string',
                'landlord_user_register_feature_'.$lang->slug.'_description_three' => 'nullable|string',
            ];
            $this->validate($request,$fields);
            foreach ($fields as $field_name => $rules){
                update_static_option($field_name,$request->$field_name);
            }
        }

        $except_langauge_fields = [
            'landlord_user_register_feature_image_one',
            'landlord_user_register_feature_image_two',
            'landlord_user_register_feature_image_three',
            'landlord_frontend_login_facebook_show_hide',
            'landlord_frontend_login_google_show_hide',
            'landlord_frontend_register_feature_show_hide',
        ];

        foreach ($except_langauge_fields as $item){
            update_static_option($item, $request->$item);
        }

        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function breadcrumb_settings()
    {
        return view('landlord.admin.other-settings');
    }

    public function update_breadcrumb_settings()
    {

    }

}
