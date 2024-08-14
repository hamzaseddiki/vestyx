<?php

namespace App\Http\Controllers\Landlord\Admin;

use App\Facades\GlobalLanguage;
use App\Helpers\ResponseMessage;
use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Models\Language;
use App\Models\Page;
use App\Models\PaymentGateway;
use App\Models\StaticOption;
use App\Models\Tenant;
use Database\Seeders\DatabaseSeeder;
use Database\Seeders\Tenant\WidgetJsonConvertSeed;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Psr\Http\Message\UriInterface;
use Spatie\Sitemap\SitemapGenerator;
use Xgenious\XgApiClient\Facades\XgApiClient;

class GeneralSettingsController extends Controller
{
    const BASE_PATH = 'landlord.admin.general-settings.';

    public function __construct()
    {
        $this->middleware('permission:general-settings-site-identity',['only'=>['site_identity','update_site_identity']]);
        $this->middleware('permission:general-settings-application-settings',['only'=>['application_settings','update_application_settings']]);
        $this->middleware('permission:general-settings-page-settings',['only'=>['page_settings','update_page_settings']]);
        $this->middleware('permission:general-settings-global-navbar-settings',['only'=>['global_variant_navbar','update_global_variant_navbar']]);
        $this->middleware('permission:general-settings-global-footer-settings',['only'=>['global_variant_footer','update_global_variant_footer']]);
        $this->middleware('permission:general-settings-basic-settings',['only'=>['basic_settings','update_basic_settings']]);
        $this->middleware('permission:general-settings-color-settings',['only'=>['color_settings','update_color_settings']]);
        $this->middleware('permission:general-settings-typography-settings',['only'=>['typography_settings','get_single_font_variant','update_typography_settings']]);
        $this->middleware('permission:general-settings-seo-settings',['only'=>['seo_settings','update_seo_settings']]);
        $this->middleware('permission:general-settings-third-party-scripts',['only'=>['update_scripts_settings','scripts_settings']]);
        $this->middleware('permission:general-settings-smtp-settings',['only'=>['email_settings','update_email_settings']]);
        $this->middleware('permission:general-settings-payment-settings',['only'=>['payment_settings','update_payment_settings']]);
        $this->middleware('permission:general-settings-custom-css',['only'=>['custom_css_settings','update_custom_css_settings']]);
        $this->middleware('permission:general-settings-custom-js',['only'=>['custom_js_settings','update_custom_js_settings']]);
        $this->middleware('permission:general-settings-licence-settings',['only'=>['license_settings','update_license_settings']]);
        $this->middleware('permission:general-settings-cache-settings',['only'=>['cache_settings','update_cache_settings']]);
    }

    public function page_settings()
    {
        $all_home_pages = Page::where(['status'=> 1])->get();
        return view(self::BASE_PATH.'page-settings',compact('all_home_pages'));
    }
    public function application_settings()
    {
        return view(self::BASE_PATH.'application-settings');
    }
    public function update_page_settings(Request $request)
    {
        $this->validate($request, [
            'home_page' => 'nullable|string',
            'pricing_plan' => 'nullable|string',
        ]);

        $fields = [
            'home_page','shop_page','pricing_plan','job_page','donation_page','event_page','knowledgebase_page','terms_condition_page','privacy_policy_page'
        ];
        foreach ($fields as $field) {
            update_static_option($field, $request->$field);
        }
        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function global_variant_navbar()
    {
        return view(self::BASE_PATH.'navbar-global-variant');
    }
    public function update_global_variant_navbar(Request $request)
    {
        $this->validate($request, [
            'global_navbar_variant' => 'nullable|string',
        ]);
        $fields = [
            'global_navbar_variant',
        ];
        foreach ($fields as $field) {
            if ($request->has($field)) {
                update_static_option($field, $request->$field);
            }
        }
        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function global_variant_footer()
    {
        return view(self::BASE_PATH.'footer-global-variant');
    }
    public function update_global_variant_footer(Request $request)
    {
        $this->validate($request, [
            'global_footer_variant' => 'nullable|string',
        ]);
        $fields = [
            'global_footer_variant',
        ];
        foreach ($fields as $field) {
            if ($request->has($field)) {
                update_static_option($field, $request->$field);
            }
        }
        return response()->success(ResponseMessage::SettingsSaved());
    }


    public function basic_settings(){
        return view(self::BASE_PATH.'basic-settings');
    }
    public function update_basic_settings(Request $request){
        $nonlang_fields = [
            'dark_mode_for_admin_panel' => 'nullable|string',
            'maintenance_mode' => 'nullable|string',
            'backend_preloader' => 'nullable|string',
            'user_email_verify_status' => 'nullable|string',
            'language_selector_status' => 'nullable|string',
            'guest_order_system_status' => 'nullable|string',
            'timezone' => 'nullable',
            'mouse_cursor_effect_status' => 'nullable',
            'section_title_extra_design_status' => 'nullable',
            'site_force_ssl_redirection' => 'nullable',
            'date_display_style' => 'nullable',
            'set_app_debug_env' => 'nullable',
            'table_list_data_orderable_status' => 'nullable',
        ];

        $this->validate($request,$nonlang_fields);
        foreach (Language::all() as $lang){
            $fields = [
                'site_'.$lang->slug.'_title'  => 'nullable|string',
                'site_'.$lang->slug.'_tag_line' => 'nullable|string',
                'site_'.$lang->slug.'_footer_copyright_text' => 'nullable|string',
            ];

            $this->validate($request,$fields);
            foreach ($fields as $field_name => $rules){
                update_static_option($field_name,$request->$field_name);
            }
        }
        foreach ($nonlang_fields as $field_name => $rules){
            update_static_option($field_name,$request->$field_name);
        }

        $app_debug_env_set = !empty($request->set_app_debug_env) ? 'true' : 'false';
        setEnvValue(['APP_DEBUG' => $app_debug_env_set]);


        $timezone = get_static_option('timezone');

        if (!empty($timezone)) {
            setEnvValue(['APP_TIMEZONE' => $timezone]);
        }

        $request_engine = $request->mysql_database_engine;
        setEnvValue(['DB_ENGINE' => $request_engine]);
        update_static_option('mysql_database_engine',$request_engine);
        update_static_option('mysql_database_engine',$request_engine);


        return response()->success(ResponseMessage::SettingsSaved());
    }
    public function update_application_settings(Request $request)
    {
        $nonlang_fields = [
            'timezone' => 'nullable',
            'set_app_name_env' => 'nullable',
            'set_environment_env' => 'nullable',
            'set_app_debug_env' => 'nullable',
        ];

        $this->validate($request,$nonlang_fields);

        foreach ($nonlang_fields as $field_name => $rules){
            update_static_option($field_name,$request->$field_name);
        }

        $app_name_env_set = !empty($request->set_app_name_env) ? "'$request->set_app_name_env'" :  '"Pica Landlord"';
        setEnvValue(['APP_NAME' => $app_name_env_set]);

        $environment_set = !empty($request->set_environment_env) ? 'production' : 'local';

        setEnvValue(['APP_ENV' => $environment_set]);

        $app_debug_env_set = !empty($request->set_app_debug_env) ? 'true' : 'false';
        setEnvValue(['APP_DEBUG' => $app_debug_env_set]);


        $timezone = get_static_option('timezone');

        if (!empty($timezone)) {
            setEnvValue(['APP_TIMEZONE' => $timezone]);
        }

        return response()->success(ResponseMessage::SettingsSaved());
    }
    public function site_identity(){
        return view(self::BASE_PATH.'site-identity');
    }
    public function update_site_identity(Request $request){
        $fields = [
            'site_logo' => 'required|integer',
            'site_white_logo' => 'required|integer',
            'site_favicon' => 'required|integer',
            'breadcrumb_left_image' => 'nullable',
            'breadcrumb_right_image' => 'nullable',
            'site_breadcrumb_image' => 'nullable',
        ];
        $this->validate($request,$fields);
        foreach ($fields as $field_name => $rules){
            update_static_option($field_name,$request->$field_name);
        }
        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function email_settings(){
        return view(self::BASE_PATH.'tenant-email-settings');
    }

    public function update_email_settings(Request $request){

        $fields = [
            'site_global_email' => 'required|email',
            'site_smtp_host' => 'required|string|regex:/^\S*$/u',
            'site_smtp_username' => 'required|string',
            'site_smtp_password' => 'required|string',
            'site_smtp_port' => 'required|numeric',
            'site_smtp_encryption' => 'required|string',
            'site_smtp_driver' => 'required|string',
        ];
        $this->validate($request,$fields);
        foreach ($fields as $field_name => $rules){
            update_static_option($field_name,$request->$field_name);
        }

        if (is_null(\tenant())){
            //for central
            update_static_option_central('site_global_email',$request->site_global_email);

            setEnvValue([
                'MAIL_MAILER'=> $request->site_smtp_driver,
                'MAIL_HOST'=> $request->site_smtp_host,
                'MAIL_PORT'=> $request->site_smtp_port,
                'MAIL_USERNAME'=>$request->site_smtp_username,
                'MAIL_PASSWORD'=> addQuotes($request->site_smtp_password),
                'MAIL_ENCRYPTION'=> $request->site_smtp_encryption,
                'MAIL_FROM_ADDRESS'=> $request->site_global_email
            ]);
        }


        return response()->success(ResponseMessage::SettingsSaved());

    }

    public  function update_mail_settings(Request $request){
        dd($request->all());
        $request->validate([

        ]);
    }


    public function color_settings(){
        return view(self::BASE_PATH.'color-settings');
    }
    public function update_color_settings(Request $request)
    {
        if (!tenant()){
                $fields = [
                    'main_color_one' => 'nullable|string|max:191',
                    'main_color_one_rgb' => 'nullable|string|max:191',
                    'main_color_two' => 'nullable|string|max:191',
                    'main_color_two_rba' => 'nullable|string|max:191',
                    'main_color_three' => 'nullable|string|max:191',
                    'heading_color' => 'nullable|string|max:191',
                    'heading_color_rgb' => 'nullable|string|max:191',
                    'secondary_color' => 'nullable|string|max:191',
                    'bg_light_one' => 'nullable|string|max:191',
                    'bg_light_two' => 'nullable|string|max:191',
                    'bg_dark_one' => 'nullable|string|max:191',
                    'bg_dark_two' => 'nullable|string|max:191',
                    'paragraph_color' => 'nullable|string|max:191',
                    'paragraph_color_two' => 'nullable|string|max:191',
                    'paragraph_color_three' => 'nullable|string|max:191',
                    'paragraph_color_four' => 'nullable|string|max:191',
                ];

            $this->validate($request, $fields);
            foreach ($fields as $field_name => $rules) {
                update_static_option($field_name, $request->$field_name);
            }
        }

        if(tenant()){
            $all_theme_fields_merge = $this->all_themes_colors_fields();
            $this->validate($request,$all_theme_fields_merge);
            foreach ($all_theme_fields_merge as $field_name => $rules){
                update_static_option($field_name,$request->$field_name);
            }
        }

        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function typography_settings(){

        $static = StaticOption::select('id','option_name','option_value')->get();
        $prefix =  is_null(tenant()) ? 'landlord' : 'tenant';
        $all_google_fonts = file_get_contents('assets/'.$prefix.'/frontend/webfonts/google-fonts.json');

        // custom font css get
        $custom_css = '/* Write Custom Css Here */';
        if (file_exists('assets/common/fonts/custom-fonts/css/custom_font.css')) {
            $custom_css = file_get_contents('assets/common/fonts/custom-fonts/css/custom_font.css');
        }

        return view(self::BASE_PATH.'typography-settings')->with([
            'google_fonts' => json_decode($all_google_fonts),
            'static_option' => $static
        ]);
    }

    public function get_single_font_variant(Request $request)
    {
        $prefix =  is_null(tenant()) ? 'landlord' : 'tenant';
        $all_google_fonts = file_get_contents('assets/'.$prefix.'/frontend/webfonts/google-fonts.json');


        $decoded_fonts = json_decode($all_google_fonts, true);
        $data = [
            'decoded_fonts' => $decoded_fonts[$request->font_family],
            'theme' => $request->theme
        ];
        return response()->json($data);
    }

    public function update_typography_settings(Request $request)
    {
       update_static_option('custom_font',$request->custom_font);

        if (tenant()) {
            $theme_suffix = ['theme_donation', 'theme_job', 'theme_event','theme_support_ticket','theme_ecommerce','theme_knowledgebase','theme_agency',
                'theme_newspaper','theme_construction','theme_consultancy','theme_wedding','theme_photography','theme_portfolio','theme_software','theme_barber_shop', 'theme_hotel_booking'
            ];

            foreach ($theme_suffix as $key => $suffix) {
                $fields[$key] = [
                    'body_font_family_'.$suffix => 'nullable|string|max:191',
                    'body_font_variant_'.$suffix => 'nullable',
                    'heading_font_'.$suffix => 'nullable|string',
                    'heading_font_family_'.$suffix => 'nullable|string|max:191',
                    'heading_font_variant_'.$suffix => 'nullable',
                ];

                $save_data[$key] = [
                    'body_font_family_'.$suffix,
                    'heading_font_family_'.$suffix,
                    'heading_font_'.$suffix
                ];

                $font_variant[$key] = [
                    'body_font_variant_'.$suffix,
                    'heading_font_variant_'.$suffix,
                ];
            }


            $fields = array_merge($fields[0], $fields[1], $fields[2]);


            $this->validate($request, $fields);

            $save_data = array_merge($save_data[0], $save_data[1], $save_data[2],$save_data[3], $save_data[4], $save_data[5],$save_data[6],$save_data[7],
                $save_data[8],$save_data[9],$save_data[10],$save_data[11],$save_data[12],$save_data[13],$save_data[14],$save_data[15]);

            foreach ($save_data as $item) {
                update_static_option($item, $request->$item);
            }

            // Issue to fix
            $font_variant = array_merge($font_variant[0], $font_variant[1], $font_variant[2], $font_variant[3],$font_variant[4],$font_variant[5],$font_variant[6],
                $font_variant[7],$font_variant[8],$font_variant[9],$font_variant[10],$font_variant[11],$font_variant[12],$font_variant[13],$font_variant[14],$font_variant[15]);


            foreach ($font_variant as $variant) {
                update_static_option($variant, serialize(!empty($request->$variant) ?  $request->$variant : ['regular']));
            }
        } else {

            $fields = [
                'body_font_family' => 'required|string|max:191',
                'heading_font' => 'nullable|string',
                'heading_font_family' => 'nullable|string|max:191',
            ];

            $this->validate($request,$fields);
            foreach ($fields as $key=> $item) {
                update_static_option($key, $request->$key);
            }

            $variants = [
                'body_font_variant' => 'required',
                'heading_font_variant' => 'nullable',
            ];

            foreach ($variants as $key=> $item) {
                update_static_option($key, serialize($request->$key));
            }

        }

        return redirect()->back()->with(['msg' => __('Typography Settings Updated..'), 'type' => 'success']);
    }

    public function add_custom_font(Request$request)
    {
        if(empty(get_static_option('custom_font')) ){
             update_static_option('custom_font','on');
        }

        $request->validate([
            'files' => 'required',
            'files.*' => 'required',
        ]);

        if($request->hasfile('files'))
        {
            foreach($request->file('files') as $key => $file)
            {
                // if($file->getClientOriginalExtension() == "ttf"){
                //     Validator::make(["font_file_".$key => $file], [
                //         "font_file_".$key => ["file","required",'mimetypes:font/ttf,font/sfnt']
                //     ])->validated();
                // }else{
                //     Validator::make(["font_file_".$key => $file], [
                //         "font_file_".$key => ["file","required",'mimes:woff,woff2,eot']
                //     ])->validated();
                // }


                if(in_array($file->getClientOriginalExtension(),['ttf','woff','woff2','eot'])){
                    if(!tenant()){
                        $name = $file->getClientOriginalName();
                        $file->move('assets/landlord/frontend/custom-fonts/', $name);
                    }else{
                        $name = $file->getClientOriginalName();
                        if(!is_dir('assets/tenant/frontend/custom-fonts/'.tenant()->id)){
                             mkdir('assets/tenant/frontend/custom-fonts/'.tenant()->id);
                        }
                        $tenant_path = 'assets/tenant/frontend/custom-fonts/'.tenant()->id.'/';
                        $file->move($tenant_path, $name);

                    }
                }else{
                    return redirect()->back()->with(['type'=> 'danger', 'msg' => __('fonts mime type is not correct')]);
                }
            }
        }


        return redirect()->back()->with(['type'=> 'success', 'msg' => __('Custom Font has been uploaded Successfully')]);

    }


    public function set_custom_font(Request $request)
    {

        update_static_option('custom_heading_font',$request->custom_heading_font);
        update_static_option('custom_body_font',$request->custom_body_font);

        return redirect()->back()->with(['type'=> 'success', 'msg' => __('Custom Font set Successfully')]);
    }

    public function delete_custom_font($font)
    {
        $path = '';
        if(!tenant()){
            $path = 'assets/landlord/frontend/custom-fonts/';
        }else{
            $path = 'assets/tenant/frontend/custom-fonts/'.tenant()->id.'/';
        }

        if(!empty($font)){
             if( file_exists($path.$font) && !is_dir($path.$font)){
                    unlink($path.$font);
             }
        }

        return redirect()->back()->with(['type'=> 'danger', 'msg' => __('Custom Font deleted Successfully')]);
    }

    public function seo_settings(){
        return view(self::BASE_PATH.'seo-settings');
    }

    public function update_seo_settings(Request $request){

        update_static_option('site_canonical_settings',$request->site_canonical_settings);

        foreach (GlobalLanguage::all_languages() as $lang){
            $fields = [
                'site_'.$lang->slug.'_meta_title'  => 'nullable|string',
                'site_'.$lang->slug.'_meta_tags' => 'nullable|string',
                'site_'.$lang->slug.'_meta_keywords' => 'nullable|string',
                'site_'.$lang->slug.'_meta_description' => 'nullable|string',
                'site_'.$lang->slug.'_meta_title' => 'nullable|string',
                'site_'.$lang->slug.'_og_meta_title' => 'nullable|string',
                'site_'.$lang->slug.'_og_meta_description' => 'nullable|string',
                'site_'.$lang->slug.'_og_meta_image' => 'nullable|string',
            ];
            $this->validate($request,$fields);
            foreach ($fields as $field_name => $rules){
                update_static_option($field_name,$request->$field_name);
            }

        }


        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function smtp_settings(){
        return view(self::BASE_PATH.'smtp-settings');
    }
    public function update_smtp_settings(Request $request){

        $fields = [
            'site_global_email' => 'required|email',
            'site_smtp_host' => 'required|string|regex:/^\S*$/u',
            'site_smtp_username' => 'required|string',
            'site_smtp_password' => 'required|string',
            'site_smtp_port' => 'required|numeric',
            'site_smtp_encryption' => 'required|string',
            'site_smtp_driver' => 'required|string',
        ];
        $this->validate($request,$fields);
        foreach ($fields as $field_name => $rules){
            update_static_option($field_name,$request->$field_name);
        }

       if (\tenant()){
           update_static_option('tenant_site_global_email',$request->site_global_email);
       }
       if (is_null(\tenant())){
           //for central
           update_static_option_central('site_global_email',$request->site_global_email);

           setEnvValue([
               'MAIL_MAILER'=> $request->site_smtp_driver,
               'MAIL_HOST'=> $request->site_smtp_host,
               'MAIL_PORT'=> $request->site_smtp_port,
               'MAIL_USERNAME'=>$request->site_smtp_username,
               'MAIL_PASSWORD'=> addQuotes($request->site_smtp_password),
               'MAIL_ENCRYPTION'=> $request->site_smtp_encryption,
               'MAIL_FROM_ADDRESS'=> $request->site_global_email
           ]);
       }

        return response()->success(ResponseMessage::SettingsSaved());
    }
    public function send_test_mail(Request $request){

        $this->validate($request,[
            'email' => 'required|email',
        ]);

        try {
            $message = __('Hi').'<br>';
            $message .= __('this is test mail');
            Mail::to($request->email)->send(new BasicMail(
                $message,__('SMTP Test email')));
        }catch (\Exception $e){
            return  response()->warning($e->getMessage());
        }

        return response()->success(ResponseMessage::mailSendSuccess());
    }

    public function cache_settings(){
        return view(self::BASE_PATH.'cache-settings');
    }
    public function update_cache_settings(Request $request){
        $this->validate($request,[
            'type' => 'required|string'
        ]);
        switch ($request->type){
            case "route":
            case "view":
            case "config":
            case "event":
            case "queue":
                try{
                    Artisan::call($request->type.':clear');
                }catch(\Exception $e){}
                break;
             default:
                try{
                    Artisan::call('cache:clear');
                    Artisan::call('optimize:clear');
                }catch(\Exception $e){}

                break;
        }
        return response()->success(ResponseMessage::success(sprintf(__('%s Cache Cleared'),ucfirst($request->type))));
    }



    public function gdpr_settings()
    {
        return view(self::BASE_PATH.'gdpr-settings');
    }

    public function update_gdpr_cookie_settings(Request $request)
    {
        $this->validate($request, [
            'site_gdpr_cookie_enabled' => 'nullable|string|max:191',
            'site_gdpr_cookie_expire' => 'required|string|max:191',
            'site_gdpr_cookie_delay' => 'required|string|max:191',
        ]);

        $all_language = Language::all();

        foreach ($all_language as $lang) {
            $this->validate($request, [
                "site_gdpr_cookie_" . $lang->slug . "_title" => 'nullable|string',
                "site_gdpr_cookie_" . $lang->slug . "_message" => 'nullable|string',
                "site_gdpr_cookie_" . $lang->slug . "_more_info_label" => 'nullable|string',
                "site_gdpr_cookie_" . $lang->slug . "_more_info_link" => 'nullable|string',
                "site_gdpr_cookie_" . $lang->slug . "_accept_button_label" => 'nullable|string',
                "site_gdpr_cookie_" . $lang->slug . "_decline_button_label" => 'nullable|string',
            ]);

            $fields = [
                "site_gdpr_cookie_" . $lang->slug . "_title",
                "site_gdpr_cookie_" . $lang->slug . "_message",
                "site_gdpr_cookie_" . $lang->slug . "_more_info_label",
                "site_gdpr_cookie_" . $lang->slug . "_more_info_link",
                "site_gdpr_cookie_" . $lang->slug . "_accept_button_label",
                "site_gdpr_cookie_" . $lang->slug . "_decline_button_label",
                "site_gdpr_cookie_" . $lang->slug . "_manage_button_label",
                "site_gdpr_cookie_" . $lang->slug . "_manage_title",
            ];

            foreach ($fields as $field){
                update_static_option($field, $request->$field);
            }

            $all_fields = [
                'site_gdpr_cookie_'.$lang->slug.'_manage_item_title',
                'site_gdpr_cookie_'.$lang->slug.'_manage_item_description',
            ];

            foreach ($all_fields as $field){
                $value = $request->$field ?? [];
                update_static_option($field,serialize($value));
            }

        }

        update_static_option('site_gdpr_cookie_delay', $request->site_gdpr_cookie_delay);
        update_static_option('site_gdpr_cookie_enabled', $request->site_gdpr_cookie_enabled);
        update_static_option('site_gdpr_cookie_expire', $request->site_gdpr_cookie_expire);

        return redirect()->back()->with(['msg' => __('GDPR Cookie Settings Updated..'), 'type' => 'success']);
    }

    public function third_party_script_settings()
    {
        return view(self::BASE_PATH.'third-party');
    }


    public function update_third_party_script_settings(Request $request)
    {

        $this->validate($request, [
            'tawk_api_key' => 'nullable|string',
            'google_adsense_id' => 'nullable|string',
            'site_third_party_tracking_code' => 'nullable|string',
            'site_google_analytics' => 'nullable|string',
            'site_google_captcha_v3_secret_key' => 'nullable|string',
            'site_google_captcha_v3_site_key' => 'nullable|string',
        ]);

        update_static_option('site_disqus_key', $request->site_disqus_key);
        update_static_option('site_google_analytics', $request->site_google_analytics);
        update_static_option('tawk_api_key', $request->tawk_api_key);
        update_static_option('site_third_party_tracking_code', $request->site_third_party_tracking_code);
        update_static_option('site_google_captcha_v3_site_key', $request->site_google_captcha_v3_site_key);
        update_static_option('site_google_captcha_v3_secret_key', $request->site_google_captcha_v3_secret_key);

        $fields = [
            'site_google_captcha_v3_secret_key',
            'site_google_captcha_v3_site_key',
            'site_third_party_tracking_code',
            'site_google_analytics',

            'social_facebook_status',
            'social_google_status',
            'google_client_id',
            'google_client_secret',
            'facebook_client_id',
            'facebook_client_secret',

            'site_third_party_tracking_code_just_after_head',
            'site_third_party_tracking_code_just_after_body',
            'site_third_party_tracking_code_just_before_body_close',

            'google_adsense_publisher_id',
            'google_adsense_customer_id',

        ];

        foreach ($fields as $field){
            update_static_option($field,$request->$field);
        }

        if(!tenant()) {
            setEnvValue([
                'GOOGLE_ADSENSE_PUBLISHER_ID' => $request->google_adsense_publisher_id,
                'GOOGLE_ADSENSE_CUSTOMER_ID' => $request->google_adsense_customer_id,
                'FACEBOOK_CLIENT_ID' => $request->facebook_client_id,
                'FACEBOOK_CLIENT_SECRET' => $request->facebook_client_secret,
                'FACEBOOK_CALLBACK_URL' => route('landlord.facebook.callback'),
                'GOOGLE_CLIENT_ID' => $request->google_client_id,
                'GOOGLE_CLIENT_SECRET' => $request->google_client_secret,
                'GOOGLE_CALLBACK_URL' => route('landlord.google.callback')
                ,
            ]);
        }


        if(tenant()){

            setEnvValue([
                'TENANT_FACEBOOK_CLIENT_ID' => $request->facebook_client_id,
                'TENANT_FACEBOOK_CLIENT_SECRET' => $request->facebook_client_secret,
                'TENANT_FACEBOOK_CALLBACK_URL' => route('tenant.facebook.callback'),
                'TENANT_GOOGLE_CLIENT_ID' => $request->google_client_id,
                'TENANT_GOOGLE_CLIENT_SECRET' => $request->google_client_secret,
                'TENANT_GOOGLE_CALLBACK_URL' => route('tenant.google.callback'),
            ]);

        }




        return redirect()->back()->with(['msg' => __('Third Party Scripts Settings Updated..'), 'type' => 'success']);
    }

    public function custom_css_settings()
    {
        $prefix =  is_null(tenant()) ? 'landlord' : 'tenant';
        $custom_css = '/* Write Custom Css Here */';
        //todo write function to check file exists or not

        if($prefix == 'landlord'){
            if (file_exists('assets/'.$prefix.'/frontend/css/dynamic-style.css')) {
                $custom_css = file_get_contents('assets/'.$prefix.'/frontend/css/dynamic-style.css',$custom_css);
            }else{
                $custom_css = file_put_contents('assets/'.$prefix.'/frontend/css/dynamic-style.css',$custom_css);
            }
        }else{
            if (file_exists('assets/'.$prefix.'/frontend/themes/css/dynamic-styles/'.tenant()->id.'-style.css')) {
                $custom_css = file_get_contents('assets/'.$prefix.'/frontend/themes/css/dynamic-styles/'.tenant()->id.'-style.css');
            }else{
                $custom_css = file_put_contents('assets/'.$prefix.'/frontend/themes/css/dynamic-styles/'.tenant()->id.'-style.css',$custom_css);
            }
        }

        return view(self::BASE_PATH.'custom-css')->with(['custom_css' => $custom_css]);
    }

    public function update_custom_css_settings(Request $request)
    {

        $prefix =  is_null(tenant()) ? 'landlord' : 'tenant';
        if($prefix === 'landlord') {
            file_put_contents('assets/' . $prefix . '/frontend/css/dynamic-style.css', $request->custom_css_area);
        }else{
            file_put_contents('assets/'.$prefix.'/frontend/themes/css/dynamic-styles/'.tenant()->id.'-style.css',$request->custom_css_area);
        }
        return redirect()->back()->with(['msg' => __('Custom Style Successfully Added...'), 'type' => 'success']);
    }

    public function custom_js_settings()
    {
        $custom_js = '/* Write Custom js Here */';
        $prefix =  is_null(tenant()) ? 'landlord' : 'tenant';


        if($prefix === 'landlord') {
            if (file_exists('assets/' . $prefix . '/frontend/js/dynamic-script.js')) {
                $custom_js = file_get_contents('assets/' . $prefix . '/frontend/js/dynamic-script.js');
            } else {
                $custom_js = file_put_contents('assets/' . $prefix . '/frontend/js/dynamic-script.js', $custom_js);
            }
        }else{
            $tenant = tenant()->id;
            if (file_exists('assets/'.$prefix.'/frontend/themes/js/dynamic-scripts/'.$tenant.'-script.js')) {
                $custom_js = file_get_contents('assets/'.$prefix.'/frontend/themes/js/dynamic-scripts/'.$tenant.'-script.js');
            }else{
                $custom_js = file_put_contents('assets/'.$prefix.'/frontend/themes/js/dynamic-scripts/'.$tenant.'-script.js',$custom_js);
            }
        }

        return view(self::BASE_PATH.'custom-js')->with(['custom_js' => $custom_js]);
    }

    public function update_custom_js_settings(Request $request)
    {
        $prefix =  is_null(tenant()) ? 'landlord' : 'tenant';

        if($prefix === 'landlord') {
            file_put_contents('assets/'.$prefix.'/frontend/js/dynamic-script.js', $request->custom_js_area);
        }else{
            file_put_contents('assets/'.$prefix.'/frontend/themes/js/dynamic-scripts/'.tenant()->id.'-script.js', $request->custom_js_area);
        }

        return redirect()->back()->with(['msg' => __('Custom Script Successfully Added...'), 'type' => 'success']);
    }


    public function database_upgrade(){
        return view(self::BASE_PATH.'database-upgrade');
    }

    public function update_database_upgrade(Request $request){

        setEnvValue(['APP_ENV' => 'local']);
        try{
            Artisan::call('migrate', ['--force' => true ]);
        }catch(\Exception $e){
            return redirect()->back()->with(['msg' => $e->getMessage(), 'type' => 'danger']);
        }

        try{

            Artisan::call('db:seed', ['--class'=> DatabaseSeeder::class,'--force' => true ]);
        }catch(\Exception $e){
            //return redirect()->back()->with(['msg' => $e->getMessage(), 'type' => 'danger']);
        }

        //todo run a query to get all the tenant then run migrate one by one...
        Tenant::latest()->chunk(50,function ($tenans)
        {
            foreach ($tenans as $tenant){
                try {
                    Artisan::call('tenants:seed', ['--class' => WidgetJsonConvertSeed::class,'--tenants'=>$tenant->id,'--force' => true]);
                }catch (\Exception $e){
                    //if issue is related to the mysql database engine,
                }
            }
        });

        Artisan::call('cache:clear');
        //todo run a query to get all the tenant then run migrate one by one...
        Tenant::latest()->chunk(50,function ($tenans){
            foreach ($tenans as $tenant){
                try {
                    Config::set("database.connections.mysql.engine","InnoDB");
                    Artisan::call('tenants:migrate', ['--force' => true,'--tenants'=>$tenant->id]);
                }catch (\Exception $e){
                    //if issue is related to the mysql database engine,
                }
            }
        });

        setEnvValue(['APP_ENV' => 'production']);
        return redirect()->back()->with(['msg' => __('Database Upgraded successfully.'), 'type' => 'success']);
    }

    public function license_settings()
    {
        return view(self::BASE_PATH.'license-settings');
    }


    public function update_license_settings(Request $request)
    {
        $this->validate($request, [
            'site_license_key' => 'required|string|max:191',
            'envato_username' => 'required|string|max:191',
        ]);

        $result = XgApiClient::activeLicense($request->site_license_key,$request->envato_username);
        $type = "danger";
        $msg = __("could not able to verify your license key, please try after sometime, if you still face this issue, contact support");
        if (!empty($result["success"]) && $result["success"]){
            update_static_option('site_license_key', $request->site_license_key);
            update_static_option('item_license_status', $result['success'] ? 'verified' : "");
            update_static_option('item_license_msg', $result['message']);
            $type = $result['success'] ? 'success' : "danger";
            $msg = $result['message'];
        }

        return redirect()->back()->with(['msg' => $msg, 'type' => $type]);
    }


    public function sitemap_settings()
    {
        if(!tenant()){
           $all_sitemap = glob('sitemap/landlord/*');

        }else{
            $all_sitemap = glob('sitemap/tenants/*');
        }

        return view(self::BASE_PATH.'sitemap-settings')->with(['all_sitemap' => $all_sitemap]);
    }


  public function update_sitemap_settings(Request $request)
    {
        $this->validate($request, [
            'site_url' => 'nullable|url',
            'title' => 'nullable|string',
        ]);

        set_time_limit(0);

        $title = Str::slug(get_static_option('site_'.default_lang().'_title')) ?? 'sitemap-'.time();

        if(!tenant()){

            $path = 'sitemap/landlord/';

            if(!\File::isDirectory($path)){
                \File::makeDirectory($path, 0777, true, true);
            }

            SitemapGenerator::create($request->site_url)
                ->shouldCrawl(function (UriInterface $url){
                    return $url->getPath();
                })
                ->writeToFile('sitemap/landlord/' . $title . '.xml');
        }else{

            $path = 'sitemap/tenants/';

            if(!\File::isDirectory($path)){
                \File::makeDirectory($path, 0777, true, true);
            }

            SitemapGenerator::create($request->site_url)
                ->shouldCrawl(function (UriInterface $url){
                    return $url->getPath();
                })
                ->writeToFile('sitemap/tenants/'. tenant()->id .'.'.env('CENTRAL_DOMAIN') . '.xml');

        }

        return redirect()->back()->with([
            'msg' => __('Sitemap Generated..'),
            'type' => 'success'
        ]);
    }

    public function delete_sitemap_settings(Request $request)
    {

        if (file_exists($request->sitemap_name)) {
            @unlink($request->sitemap_name);
        }
        return redirect()->back()->with(['msg' => __('Sitemap Deleted...'), 'type' => 'danger']);
    }


    private function all_themes_colors_fields() : array
    {
        $donation_color_fields = [
            'donation_main_color_one' => 'nullable|string|max:191',
            'donation_main_color_one_rgb' => 'nullable|string|max:191',
            'donation_main_color_two' => 'nullable|string|max:191',
            'donation_main_color_two_rba' => 'nullable|string|max:191',
            'donation_heading_color' => 'nullable|string|max:191',
            'donation_heading_color_rgb' => 'nullable|string|max:191',
            'donation_secondary_color' => 'nullable|string|max:191',
            'donation_bg_light_one' => 'nullable|string|max:191',
            'donation_bg_light_two' => 'nullable|string|max:191',
            'donation_bg_dark_one' => 'nullable|string|max:191',
            'donation_bg_dark_two' => 'nullable|string|max:191',
            'donation_paragraph_color' => 'nullable|string|max:191',
            'donation_paragraph_color_two' => 'nullable|string|max:191',
            'donation_paragraph_color_three' => 'nullable|string|max:191',
            'donation_paragraph_color_four' => 'nullable|string|max:191',
        ];

        $job_color_fields = [
            'job_main_color_one' => 'nullable|string|max:191',
            'job_main_color_one_rgb' => 'nullable|string|max:191',
            'job_main_color_two' => 'nullable|string|max:191',
            'job_main_color_two_rba' => 'nullable|string|max:191',
            'job_heading_color' => 'nullable|string|max:191',
            'job_heading_color_rgb' => 'nullable|string|max:191',
            'job_heading_color_two' => 'nullable|string|max:191',
            'job_btn_color_one' => 'nullable|string|max:191',
            'job_btn_color_two' => 'nullable|string|max:191',
            'job_section_bg_one' => 'nullable|string|max:191',
            'job_scroll_bar_bg' => 'nullable|string|max:191',
            'job_scroll_bar_color' => 'nullable|string|max:191',
            'job_paragraph_color' => 'nullable|string|max:191',
            'job_paragraph_color_two' => 'nullable|string|max:191',
        ];

        $event_color_fields = [
            'event_main_color_one' => 'nullable|string|max:191',
            'event_main_color_one_rgb' => 'nullable|string|max:191',
            'event_main_color_two' => 'nullable|string|max:191',
            'event_main_color_two_rba' => 'nullable|string|max:191',
            'event_heading_color' => 'nullable|string|max:191',
            'event_heading_color_rgb' => 'nullable|string|max:191',
            'event_secondary_color' => 'nullable|string|max:191',
            'event_bg_light_one' => 'nullable|string|max:191',
            'event_bg_light_two' => 'nullable|string|max:191',
            'event_bg_dark_one' => 'nullable|string|max:191',
            'event_bg_dark_two' => 'nullable|string|max:191',
            'event_paragraph_color' => 'nullable|string|max:191',
            'event_paragraph_color_two' => 'nullable|string|max:191',
            'event_paragraph_color_three' => 'nullable|string|max:191',
            'event_paragraph_color_four' => 'nullable|string|max:191',
            'event_button_color_one' => 'nullable|string|max:191',
            'event_button_color_two' => 'nullable|string|max:191',
            'event_btn_color_one' => 'nullable|string|max:191',
        ];

        $support_ticket_color_fields = [
            'support_ticket_main_color_one' => 'nullable|string|max:191',
            'support_ticket_main_color_one_rgb' => 'nullable|string|max:191',
            'support_ticket_main_color_two' => 'nullable|string|max:191',
            'support_ticket_main_color_two_rba' => 'nullable|string|max:191',
            'support_ticket_heading_color' => 'nullable|string|max:191',
            'support_ticket_heading_color_rgb' => 'nullable|string|max:191',
            'support_ticket_heading_color_two' => 'nullable|string|max:191',
            'support_ticket_btn_color_one' => 'nullable|string|max:191',
            'support_ticket_btn_color_two' => 'nullable|string|max:191',
            'support_ticket_section_bg_one' => 'nullable|string|max:191',
            'support_ticket_scroll_bar_bg' => 'nullable|string|max:191',
            'support_ticket_scroll_bar_color' => 'nullable|string|max:191',
            'support_ticket_paragraph_color' => 'nullable|string|max:191',
            'support_ticket_paragraph_color_two' => 'nullable|string|max:191',
        ];

        $ecommerce_color_fields = [
            'ecommerce_main_color_one' => 'nullable|string|max:191',
            'ecommerce_main_color_one_rgb' => 'nullable|string|max:191',
            'ecommerce_main_color_two' => 'nullable|string|max:191',
            'ecommerce_main_color_two_rba' => 'nullable|string|max:191',
            'ecommerce_heading_color' => 'nullable|string|max:191',
            'ecommerce_heading_color_two' => 'nullable|string|max:191',
            'ecommerce_heading_color_rgb' => 'nullable|string|max:191',
            'ecommerce_btn_color_one' => 'nullable|string|max:191',
            'ecommerce_btn_color_two' => 'nullable|string|max:191',
            'ecommerce_scroll_bar_bg' => 'nullable|string|max:191',
            'ecommerce_scroll_bar_color' => 'nullable|string|max:191',
            'ecommerce_bg_light_one' => 'nullable|string|max:191',
            'ecommerce_bg_light_two' => 'nullable|string|max:191',
            'ecommerce_bg_dark_one' => 'nullable|string|max:191',
            'ecommerce_bg_dark_two' => 'nullable|string|max:191',
            'ecommerce_paragraph_color' => 'nullable|string|max:191',
            'ecommerce_paragraph_color_two' => 'nullable|string|max:191',
            'ecommerce_paragraph_color_three' => 'nullable|string|max:191',
            'ecommerce_paragraph_color_four' => 'nullable|string|max:191',
            'ecommerce_stock_color' => 'nullable|string|max:191',
        ];

        $knowledgebase_color_fields = [
            'knowledgebase_main_color_one' => 'nullable|string|max:191',
            'knowledgebase_main_color_one_rgb' => 'nullable|string|max:191',
            'knowledgebase_main_color_two' => 'nullable|string|max:191',
            'knowledgebase_main_color_two_rba' => 'nullable|string|max:191',
            'knowledgebase_heading_color' => 'nullable|string|max:191',
            'knowledgebase_heading_color_rgb' => 'nullable|string|max:191',
            'knowledgebase_heading_color_two' => 'nullable|string|max:191',
            'knowledgebase_btn_color_one' => 'nullable|string|max:191',
            'knowledgebase_btn_color_two' => 'nullable|string|max:191',
            'knowledgebase_section_bg_one' => 'nullable|string|max:191',
            'knowledgebase_section_bg_two' => 'nullable|string|max:191',
            'knowledgebase_scroll_bar_bg' => 'nullable|string|max:191',
            'knowledgebase_scroll_bar_color' => 'nullable|string|max:191',
            'knowledgebase_paragraph_color' => 'nullable|string|max:191',
            'knowledgebase_paragraph_color_two' => 'nullable|string|max:191',
        ];

        $agency_color_fields = [
            'agency_main_color_one' => 'nullable|string|max:191',
            'agency_main_color_one_rgb' => 'nullable|string|max:191',
            'agency_agency_section_bg' => 'nullable|string|max:191',
            'agency_agency_section_bg_2' => 'nullable|string|max:191',
            'agency_agency_section_bg_3' => 'nullable|string|max:191',
            'agency_heading_color' => 'nullable|string|max:191',
            'agency_body_color' => 'nullable|string|max:191',
            'agency_light_color' => 'nullable|string|max:191',
            'agency_review_color' => 'nullable|string|max:191',
        ];

        $newspaper_color_fields = [
            'newspaper_main_color_one' => 'nullable|string|max:191',
            'newspaper_main_color_one_rgb' => 'nullable|string|max:191',
            'newspaper_secondary_color'=> 'nullable',
            'newspaper_secondary_color_rgb' => 'nullable',
            'newspaper_newspaper_section_bg' => 'nullable',
            'newspaper_newspaper_section_bg_2' => 'nullable',
            'newspaper_border_color' => 'nullable',
            'newspaper_border_color_2' => 'nullable',
            'newspaper_heading_color' => 'nullable',
            'newspaper_body_color' => 'nullable',
            'newspaper_light_color' => 'nullable',
            'newspaper_review_color'=> 'nullable|string|max:191'
        ];


        $construction_color_fields = [
            'construction_main_color_one' => 'nullable|string|max:191',
            'construction_main_color_one_rgb' => 'nullable|string|max:191',
            'construction_main_color_two'=> 'nullable',
            'construction_main_color_two_rgb' => 'nullable',
            'construction_section_bg' => 'nullable',
            'construction_section_bg_2' => 'nullable',
            'construction_section_bg_3' => 'nullable',
            'construction_white' => 'nullable',
            'construction_white_rgb' => 'nullable',
            'construction_black' => 'nullable',
            'construction_black_rgb' => 'nullable',
            'construction_border_color'=> 'nullable|string|max:191',
            'construction_border_color_two'=> 'nullable|string|max:191',
            'construction_heading_color'=> 'nullable|string|max:191',
            'construction_body_color'=> 'nullable|string|max:191',
            'construction_paragraph_color'=> 'nullable|string|max:191',
            'construction_light_color'=> 'nullable|string|max:191',
            'construction_review_color'=> 'nullable|string|max:191',
        ];


        $consultancy_color_fields = [
            'consultancy_main_color_one' => 'nullable|string',
            'consultancy_main_color_one_rgb' => 'nullable|string',
            'consultancy_main_color_two'=> 'nullable',
            'consultancy_main_color_two_rgb' => 'nullable',
            'consultancy_section_bg' => 'nullable',
            'consultancy_section_bg_2' => 'nullable',
            'consultancy_section_bg_3' => 'nullable',
            'consultancy_white' => 'nullable',
            'consultancy_white_rgb' => 'nullable',
            'consultancy_black' => 'nullable',
            'consultancy_black_rgb' => 'nullable',
            'consultancy_border_color'=> 'nullable|string|max:191',
            'consultancy_border_color_two'=> 'nullable|string|max:191',
            'consultancy_heading_color'=> 'nullable|string|max:191',
            'consultancy_body_color'=> 'nullable|string|max:191',
            'consultancy_paragraph_color'=> 'nullable|string|max:191',
            'consultancy_light_color'=> 'nullable|string|max:191',
            'consultancy_review_color'=> 'nullable|string|max:191',
        ];

        $wedding_color_fields = [
            'wedding_main_color_one' => 'nullable|string',
            'wedding_main_color_one_rgb' => 'nullable|string',
            'wedding_secondary_color'=> 'nullable',
            'wedding_secondary_color_rgb' => 'nullable',
            'wedding_section_bg_secondary' => 'nullable',
            'wedding_section_bg' => 'nullable',
            'wedding_section_bg_2' => 'nullable',
            'wedding_section_bg_3' => 'nullable',
            'wedding_white' => 'nullable',
            'wedding_white_rgb' => 'nullable',
            'wedding_black' => 'nullable',
            'wedding_black_rgb'=> 'nullable|string|max:191',
            'wedding_border_color'=> 'nullable|string|max:191',
            'wedding_border_color_two'=> 'nullable|string|max:191',
            'wedding_success_color'=> 'nullable|string|max:191',
            'wedding_delete_color'=> 'nullable|string|max:191',
            'wedding_heading_color'=> 'nullable|string|max:191',
            'wedding_body_color'=> 'nullable|string|max:191',
            'wedding_paragraph_color'=> 'nullable|string|max:191',
            'wedding_paragraph_color_two'=> 'nullable|string|max:191',
            'wedding_light_color'=> 'nullable|string|max:191',
            'wedding_review_color'=> 'nullable|string|max:191',
        ];

        $photography_color_fields = [
            'photography_main_color_one' => 'nullable|string',
            'photography_main_color_one_rgb' => 'nullable|string',
            'photography_main_color_two' => 'nullable|string',
            'photography_main_color_two_rgb' => 'nullable|string',
            'photography_secondary_color'=> 'nullable',
            'photography_secondary_color_rgb' => 'nullable',
            'photography_section_bg' => 'nullable',
            'photography_section_bg_2' => 'nullable',
            'photography_section_bg_3' => 'nullable',
            'photography_white' => 'nullable',
            'photography_white_rgb' => 'nullable',
            'photography_black' => 'nullable',
            'photography_black_rgb'=> 'nullable|string|max:191',
            'photography_border_color'=> 'nullable|string|max:191',
            'photography_border_color_two'=> 'nullable|string|max:191',
            'photography_success_color'=> 'nullable|string|max:191',
            'photography_delete_color'=> 'nullable|string|max:191',
            'photography_heading_color'=> 'nullable|string|max:191',
            'photography_body_color'=> 'nullable|string|max:191',
            'photography_paragraph_color'=> 'nullable|string|max:191',
            'photography_paragraph_color_two'=> 'nullable|string|max:191',
            'photography_light_color'=> 'nullable|string|max:191',
            'photography_review_color'=> 'nullable|string|max:191',
        ];

        $portfolio_color_fields = [
            'portfolio_main_color_one' => 'nullable|string',
            'portfolio_main_color_one_rgb' => 'nullable|string',
            'portfolio_secondary_color'=> 'nullable',
            'portfolio_secondary_color_rgb' => 'nullable',
            'portfolio_section_bg' => 'nullable',
            'portfolio_section_bg_2' => 'nullable',
            'portfolio_section_bg_3' => 'nullable',
            'portfolio_white' => 'nullable',
            'portfolio_white_rgb' => 'nullable',
            'portfolio_black' => 'nullable',
            'portfolio_black_rgb'=> 'nullable|string|max:191',
            'portfolio_border_color'=> 'nullable|string|max:191',
            'portfolio_border_color_two'=> 'nullable|string|max:191',
            'portfolio_success_color'=> 'nullable|string|max:191',
            'portfolio_delete_color'=> 'nullable|string|max:191',
            'portfolio_heading_color'=> 'nullable|string|max:191',
            'portfolio_body_color'=> 'nullable|string|max:191',
            'portfolio_paragraph_color'=> 'nullable|string|max:191',
            'portfolio_paragraph_color_two'=> 'nullable|string|max:191',
            'portfolio_light_color'=> 'nullable|string|max:191',
            'portfolio_review_color'=> 'nullable|string|max:191',
        ];

        $software_color_fields = [
            'software_main_color_one' => 'nullable|string',
            'software_main_color_one_rgb' => 'nullable|string',
            'software_secondary_color'=> 'nullable',
            'software_secondary_color_rgb' => 'nullable',
            'software_section_bg' => 'nullable',
            'software_section_bg_secondary' => 'nullable',
            'software_section_bg_2' => 'nullable',
            'software_section_bg_3' => 'nullable',
            'software_white' => 'nullable',
            'software_white_rgb' => 'nullable',
            'software_black' => 'nullable',
            'software_black_rgb'=> 'nullable|string|max:191',
            'software_border_color'=> 'nullable|string|max:191',
            'software_border_color_two'=> 'nullable|string|max:191',
            'software_success_color'=> 'nullable|string|max:191',
            'software_heading_color'=> 'nullable|string|max:191',
            'software_body_color'=> 'nullable|string|max:191',
            'software_paragraph_color'=> 'nullable|string|max:191',
            'software_paragraph_color_two'=> 'nullable|string|max:191',
            'software_light_color'=> 'nullable|string|max:191',
            'software_review_color'=> 'nullable|string|max:191',
        ];

        $barber_color_fields = [
            'barber_shop_main_color_one' => 'nullable|string',
            'barber_shop_main_color_one_rgb' => 'nullable|string',
            'barber_shop_secondary_color'=> 'nullable',
            'barber_shop_secondary_color_rgb' => 'nullable',
            'barber_shop_section_bg' => 'nullable',
            'barber_shop_section_bg_main' => 'nullable',
            'barber_shop_section_bg_2' => 'nullable',
            'barber_shop_section_bg_3' => 'nullable',
            'barber_shop_white' => 'nullable',
            'barber_shop_white_rgb' => 'nullable',
            'barber_shop_black' => 'nullable',
            'barber_shop_black_rgb'=> 'nullable|string|max:191',
            'barber_shop_border_color'=> 'nullable|string|max:191',
            'barber_shop_border_color_two'=> 'nullable|string|max:191',
            'barber_shop_success_color'=> 'nullable|string|max:191',
            'barber_shop_delete_color'=> 'nullable|string|max:191',
            'barber_shop_heading_color'=> 'nullable|string|max:191',
            'barber_shop_body_color'=> 'nullable|string|max:191',
            'barber_shop_paragraph_color'=> 'nullable|string|max:191',
            'barber_shop_paragraph_color_two'=> 'nullable|string|max:191',
            'barber_shop_light_color'=> 'nullable|string|max:191',
            'barber_shop_review_color'=> 'nullable|string|max:191',
        ];

        $hotel_booking_color_fields = [
            'hotel_booking_main_color_one' => 'nullable|string',
            'hotel_booking_main_color_one_rgb' => 'nullable|string',
            'hotel_booking_secondary_color'=> 'nullable',
            'hotel_booking_secondary_color_rgb' => 'nullable',
            'hotel_booking_section_bg_1' => 'nullable',
            'hotel_booking_section_bg_2' => 'nullable',
            'hotel_booking_success_color'=> 'nullable|string|max:191',
            'hotel_booking_heading_color'=> 'nullable|string|max:191',
            'hotel_booking_body_color'=> 'nullable|string|max:191',
            'hotel_booking_footer_bg_1'=> 'nullable|string|max:191',
            'hotel_booking_footer_bg_2'=> 'nullable|string|max:191',
            'hotel_booking_copyright_bg_1'=> 'nullable|string|max:191',
            'hotel_booking_light_color'=> 'nullable|string|max:191',
            'hotel_booking_review_color'=> 'nullable|string|max:191',
            'hotel_booking_gray_color'=> 'nullable|string|max:191',
            'hotel_booking_input_color'=> 'nullable|string|max:191',
        ];

        return array_merge(
            $donation_color_fields,
            $job_color_fields,
            $event_color_fields,
            $support_ticket_color_fields,
            $ecommerce_color_fields,
            $knowledgebase_color_fields,
            $agency_color_fields,
            $newspaper_color_fields,
            $construction_color_fields,
            $consultancy_color_fields,
            $wedding_color_fields,
            $photography_color_fields,
            $portfolio_color_fields,
            $software_color_fields,
            $barber_color_fields,
            $hotel_booking_color_fields
        );
    }

    public function software_update_check_settings(Request $request){
        //todo run app update and database migrate here for test...
        return view(self::BASE_PATH."check-update");
    }

    public function update_version_check(Request $request){

        $result = XgApiClient::checkForUpdate(get_static_option("site_license_key"),get_static_option_central("get_script_version"));

        if (isset($result["success"]) && $result["success"]){


            $productUid = $result['data']['product_uid'] ?? null;
            $clientVersion = $result['data']['client_version'] ?? null;
            $latestVersion = $result['data']['latest_version'] ?? null;
            $productName = $result['data']['product'] ?? null;
            $releaseDate =  $result['data']['release_date'] ?? null;
            $changelog =  $result['data']['changelog'] ?? null;
            $phpVersionReq =  $result['data']['php_version'] ?? null;
            $mysqlVersionReq =  $result['data']['mysql_version'] ?? null;
            $extensions =  $result['data']['extension'] ?? null;
            $isTenant =  $result['data']['is_tenant'] ?? null;
            $daysDiff = $releaseDate;
            $msg = $result['data']['message'] ?? null;

            $output = "";
            $phpVCompare = version_compare(number_format((float) PHP_VERSION, 1), $phpVersionReq == 8 ? '8.0' : $phpVersionReq, '>=');
            $mysqlServerVersion = DB::select('select version()')[0]->{'version()'};
            $mysqlVCompare = version_compare(number_format((float) $mysqlServerVersion, 1), $mysqlVersionReq, '<=');
            $extensionReq = true;
            if ($extensions) {
                foreach (explode(',', str_replace(' ','', strtolower($extensions))) as $extension) {
                    if(!empty($extension)) continue;
                    $extensionReq = XgApiClient::extensionCheck($extension);
                }
            }
            if(($phpVCompare === false || $mysqlVCompare === false) && $extensionReq === false){
                $output .='<div class="text-danger">'.__('Your server does not have required software version installed.  Required: Php'). $phpVersionReq == 8 ? '8.0' : $phpVersionReq .', Mysql'.  $mysqlVersionReq . '/ Extensions:' .$extensions . 'etc </div>';
                return response()->json(["msg" => $result["message"],"type" => "success","markup" => $output ]);
            }

            if (!empty($latestVersion)){
                $output .= '<div class="text-success">'.$msg.'</div>';
                $output .= '<div class="card text-center" ><div class="card-header bg-transparent text-warning" >'.__("Please backup your database & script files before upgrading.").'</div>';
                $output .= '<div class="card-body" ><h5 class="card-title" >'.__("new Version").' ('.$latestVersion.') '.__("is Available for").' '.$productName.'!</h5 >';
                $updateActionUrl = route('landlord.admin.general.update.download.settings', [$productUid, $isTenant]);
                $output .= '<a href = "#"  class="btn btn-warning" id="update_download_and_run_update" data-version="'.$latestVersion.'" data-action="'.$updateActionUrl.'"> <i class="las la-spinner la-spin d-none"></i>'.__("Download & Update").' </a>';
                $output .= '<small class="text-warning d-block">'.__('it can take upto 5-10min to complete update download and initiate upgrade').'</small></div>';
                $changesLongByLine = explode("\n",$changelog);
                $output .= '<p class="changes-log">';
                $output .= '<strong>'.__("Released:")." ".$daysDiff." "."</strong><br>";
                $output .= "-------------------------------------------<br>";
                foreach($changesLongByLine as $cg){
                    $output .= $cg."<br>";
                }
                $output .= '</p>';

                $output .='</div>';
            }

            return response()->json(["msg" => $result["message"],"type" => "success","markup" => $output ]);
        }

        return response()->json(["msg" => $result["message"],"type" => "danger","markup" => "<p class='text-danger'>".$result["message"]."</p>" ]);

    }

    public function updateDownloadLatestVersion($productUid, $isTenant){

        $version = \request()->get("version");
        //todo wrap this function through xgapiclient facades
        $getItemLicenseKey = get_static_option('site_license_key');
        $return_val = XgApiClient::downloadAndRunUpdateProcess($productUid, $isTenant,$getItemLicenseKey,$version);

        if (is_array($return_val)){
            return response()->json(['msg' => $return_val['msg'] , 'type' => $return_val['type']]);
        }elseif (is_bool($return_val) && $return_val){
            return response()->json(['msg' => __('system upgrade success') , 'type' => 'success']);
        }
        //it is false
        return response()->json(['msg' => __('Update failed, please contact support for further assistance') , 'type' => 'danger']);
    }

    public function license_key_generate(Request $request){
        $request->validate([
            "envato_purchase_code" => "required",
            "envato_username" => "required",
            "email" => "required",
        ]);
        $res = XgApiClient::VerifyLicense(purchaseCode: $request->envato_purchase_code, email: $request->email, envatoUsername: $request->envato_username);
        $type = $res["success"] ? "success" : "danger";
        $message = $res["message"];
        //store information in database
        if (!empty($res["success"])){
            //success verify
            $res["data"] = is_array($res["data"]) ? $res["data"] : (array) $res["data"];
            update_static_option("license_product_uuid",$res["data"]["product_uid"] ?? "");
            update_static_option("site_license_key",$res["data"]["license_key"] ?? "");
        }
        update_static_option("license_purchase_code",$request->envato_purchase_code);
        update_static_option("license_email",$request->email);
        update_static_option("license_username",$request->envato_username);

        return back()->with(["msg" => $message, "type" => $type]);
    }



}
