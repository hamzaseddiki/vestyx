<?php

namespace App\Http\Controllers\Landlord\Admin;

use App\Facades\GlobalLanguage;
use App\Helpers\ResponseMessage;
use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Models\CustomDomain;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CustomDomainController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    private const ROOT_PATH = 'landlord.admin.custom-domain.';

    public function all_pending_custom_domain_requests(){
        $domain_infos = CustomDomain::whereNot('custom_domain_status','connected')->get();
        return view(self::ROOT_PATH.'all-pending-requests')->with(['domain_infos' => $domain_infos]);
    }

    public function all_domain_requests()
    {
        $domain_infos = CustomDomain::whereNotNull('custom_domain')->get();
        return view(self::ROOT_PATH.'all-requests')->with(['domain_infos' => $domain_infos]);
    }

    public function update_custom_domain(Request $request)
    {

        $request->validate([
            'custom_domain' => 'required|regex:/^[a-za-z.-]+$/',
        ]);


        if(str_contains('www',$request->custom_domain)){
            $msg = __('www is not allowed');
            return response()->danger(ResponseMessage::delete($msg));
        }


        if(str_contains('.',$request->custom_domain)){
            $msg = __('only dot is not allowed');
            return response()->danger(ResponseMessage::delete($msg));
        }

        $custom_domain_id = $request->custom_domain_id;
        $domain = $request->custom_domain;

        $custom_domain = CustomDomain::findOrFail($custom_domain_id);
        $custom_domain->custom_domain = $domain;
        $custom_domain->save();

        return response()->success(ResponseMessage::SettingsSaved('Custom domain updated'));
    }

    public function status_change(Request $request)
    {
         $request->validate([
            'custom_domain_id' => 'required',
            'custom_domain_status' => 'required'
        ]);

        $custom_domain = CustomDomain::findOrFail($request->custom_domain_id);
        $custom_domain->custom_domain_status = $request->custom_domain_status;
        $custom_domain->save();

        $full_custom_domain = $custom_domain->custom_domain;

        if($request->custom_domain_status == 'connected'){
             DB::table('domains')->where('tenant_id',$custom_domain->old_domain)->update(['domain' => $full_custom_domain]);
        }

        $domain_status_color = ['pending' => '#ffc107', 'in_progress' => 'blue', 'connected' => 'green', 'removed' => 'red', 'rejected' => 'red'];
        $domain_status = '<b style="color:'.$domain_status_color[$request->custom_domain_status].';text-transform: uppercase">'.str_replace('_', ' ', $request->custom_domain_status).'</b>';
        $email = optional(optional(optional($custom_domain)->tenant)->user)->email;
        $message = __('We have reviewed your request and took an action on your custom domain request. Your custom domain new status is '.$domain_status);
        $subject =  __('Custom domain request message');
        $mail_status = __("mail send success");
        try {
            Mail::to($email)->send(new BasicMail($message,$subject));
        }catch (\Exception $ex){
            $mail_status = __("mail send failed");
        }

        return response()->success(ResponseMessage::SettingsSaved('Custom domain status change settings saved').' '.$mail_status);
    }


    public function delete_request($id)
    {
        $custom_domain = CustomDomain::findOrFail($id);
        $custom_domain->custom_domain_status = 'removed';
        $custom_domain->save();

        return response()->success(ResponseMessage::SettingsSaved('Custom domain deleted successfully'));
    }


    public function bulk_action(Request $request){
        $all = CustomDomain::find($request->ids);
        foreach($all as $item){
            $item->custom_domain_status = 'removed';
            $item->save();
        }
        return response()->json(['status' => 'ok']);
    }

    public function settings()
    {
        return view(self::ROOT_PATH.'settings');
    }

    public function update_settings(Request $request)
    {
        foreach (GlobalLanguage::all_languages() as $lang){
            $data =  $request->validate([
                'custom_domain_settings_'.$lang->slug.'_title' =>  'nullable',
                'custom_domain_settings_'.$lang->slug.'_description' =>  'nullable',
                'custom_domain_table_'.$lang->slug.'_title' =>  'nullable',
            ]);


            foreach ($data as $key => $item){
                update_static_option_central($key,$request->$key);
            }
        }

        update_static_option_central('custom_domain_settings_show_image',$request->custom_domain_settings_show_image);

        return response()->success(ResponseMessage::SettingsSaved());
    }



}
