<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Helpers\ResponseMessage;
use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use function __;
use function response;
use function view;

class FrontendUserManageController extends Controller
{
    const BASE_PATH = 'tenant.admin.frontend-user.';

    public function all_users(){
        $all_users = User::latest()->paginate(10);
        return view(self::BASE_PATH.'index',compact('all_users'));
    }

    public function new_user()
    {
        return view(self::BASE_PATH.'new');
    }

    public function new_user_store(Request $request)
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
        ]);

        User::create([
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

        return response()->success(ResponseMessage::success(__('Tenant has been created successfully..!')));

    }

    public function edit_profile($id)
    {

        $user = User::find($id);
        return view(self::BASE_PATH.'edit',compact('user'));
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
        $user = User::find($id);
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

        $token = $subscriber_details->email_verify_token ? $subscriber_details->email_verify_token  : Str::random(32);

        if (empty($subscriber_details->email_verify_token)){
            $subscriber_details->email_verify_token = $token;
            $subscriber_details->save();
        }

        $message = __('verify your email to get all news from '). get_static_option('site_'.get_default_language().'_title') . '<div class="btn-wrap"> <a class="anchor-btn" href="' . route('tenant.user.login') . '">' . __('Login') . '</a></div>';

        $msg = $message;
        $subject = __('verify your email');


        try {

            Mail::to($subscriber_details->email)->send(new BasicMail($msg,$subject));

        }catch (\Exception $ex){
            return response()->danger(ResponseMessage::delete($ex->getMessage()));
        }

        return response()->success(ResponseMessage::success(__('Email Verify Mail Send Successfully..!')));
    }

}
