<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Events\TenantNotificationEvent;
use App\Helpers\ResponseMessage;
use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Models\CustomDomain;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Stancl\Tenancy\Database\Models\Domain;
use function App\Http\Controllers\Tenant\Admin\str_contains;

class CustomDomainController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    private const ROOT_PATH = 'tenant.admin.custom-domain.';

    public function custom_domain_request(){
        $user_domain_infos = tenant()->user()->first();
        $custom_domain_info = CustomDomain::where('user_id',$user_domain_infos->id)->first();
        return view(self::ROOT_PATH.'custom-domain')->with(['user_domain_infos' => $user_domain_infos, 'custom_domain_info'=>$custom_domain_info]);
    }


    public function custom_domain_request_change(Request $request)
    {

        $request->validate([
            'old_domain' => 'required',
            'custom_domain' => 'required|regex:/^([a-zA-Z0-9-]+\.)+[a-zA-Z0-9]{2,}+$/',
        ]);


        if(\str_contains('www',$request->custom_domain)){
            $msg = __('www is not allowed');
            return response()->danger(ResponseMessage::delete($msg));
        }


        if(\str_contains('.',$request->custom_domain)){
            $msg = __('only dot is not allowed');
            return response()->danger(ResponseMessage::delete($msg));
        }

          $all_tenant = Domain::where('tenant_id',$request->custom_domain)->first();

            if(!is_null($all_tenant)){
                return response()->danger(ResponseMessage::delete('You can not add this as your domain, this is reserved to landlord hosting domain'));
            }

        $tenant = tenant();

        CustomDomain::updateOrCreate(
            [
                'user_id' => $request->user_id,
                'old_domain' => $request->old_domain
            ],
            [
                'user_id' => $request->user_id,
                'old_domain' => $request->old_domain,
                'custom_domain' => $request->custom_domain,
                'custom_domain_status' => 'pending'
            ]
        );

        $message_body = __('You have a custom domain request from ')  . $tenant->user?->name . __(' of ') . ' : ' .'('. $tenant->id . ')'. ' to ' .'(' . $request->custom_domain. ')';
        $subject = __('Custom domain request');
        $mail_status = __("mail send success");

        try {
            Mail::to(get_static_option_central('site_global_email'))->send(new BasicMail($message_body,$subject));
        } catch (\Exception $e) {
            $mail_status = __("mail send failed");
        }

        return response()->success(ResponseMessage::SettingsSaved('Custom domain change request sent successfully..!').' '.$mail_status);
    }


}
