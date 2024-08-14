<?php

namespace App\Http\Controllers\Landlord\Admin;

use App\Helpers\ResponseMessage;
use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Models\Newsletter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class NewsletterController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:newsletter-list|newsletter-create|newsletter-mail-send|newsletter-delete',['only' => ['index']]);
        $this->middleware('permission:newsletter-create',['only' => ['add_new_sub']]);
        $this->middleware('permission:newsletter-send-mail',['only' => ['send_mail_all_index','send_mail','verify_mail_send','send_mail_all']]);
        $this->middleware('permission:newsletter-delete',['only' => ['delete','bulk_action']]);
    }
    public function index()
    {
        $all_newsletter = Newsletter::latest()->get();
        return view('landlord.admin.newsletter.newsletter',compact('all_newsletter'));
    }

    public function send_mail(Request $request){

        $this->validate($request,[
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required',
        ]);

        $message = $request->message;
        $subject =  $request->subject;
        $id = $request->id;

        $unsubscribe_route = route(route_prefix().'newsletter.unsubscribe',$id); //as extra param

        try {
            Mail::to($request->email)->send(new BasicMail($message,$subject,null,null,$unsubscribe_route));
        }catch (\Exception $ex){
            return redirect()->back()->with(ResponseMessage::delete($ex->getMessage()));
        }

        return redirect()->back()->with([
            'msg' => __('Mail Send Success...'),
            'type' => 'success'
        ]);
    }
    public function delete($id){
        Newsletter::find($id)->delete();
        return redirect()->back()->with(ResponseMessage::delete());
    }

    public function send_mail_all_index(){
        return view('landlord.admin.newsletter.send-mail-to-all');
    }

    public function send_mail_all(Request $request){
        $this->validate($request,[
            'subject' => 'required',
            'message' => 'required',
        ]);
        $all_subscriber = Newsletter::all();

        foreach ($all_subscriber as $subscriber){
            $message = $request->message;
            $subject =  $request->subject;
            $unsubscribe_route = route(route_prefix().'newsletter.unsubscribe',$subscriber->id); //as extra param

            try {
                Mail::to($subscriber->email)->send(new BasicMail($message,$subject,null,null,$unsubscribe_route));
            }catch (\Exception $ex){
                //return redirect()->back()->with(['msg' => ResponseMessage::delete($ex->getMessage()),'type' => 'danger']);
            }
        }
        return response()->success(__('Mail Send Success'));
    }

    public function add_new_sub(Request $request){

        $this->validate($request,[
            'email' => 'required|email|unique:newsletters'
        ],
            [
                'email.required' => __('email field required')
            ]);

        $verify_token = Str::random(32);
        Newsletter::create([
            'email' => $request->email,
            'verified' => 0,
            'token' => $verify_token
        ]);
        return redirect()->back()->with([
            'msg' => __('New Subscriber Added..'),
            'type' => 'success'
        ]);
    }

    public function bulk_action(Request $request){
        $all = Newsletter::find($request->ids);
        foreach($all as $item){
            $item->delete();
        }
        return response()->json(['status' => 'ok']);
    }

    public function verify_mail_send(Request $request){

        $subscriber_details = Newsletter::findOrFail($request->id);
        $token = $subscriber_details->token ?? Str::random(32);

        if (empty($subscriber_details->token)){
            $subscriber_details->token = $token;
            $subscriber_details->save();
        }

        $route = route(route_prefix().'subscriber.verify', ['token' => $token]);
        $msg = __('Hello,') . '<br>';
        $msg.= __('Verify your email to get all news from '). get_static_option('site_'.get_user_lang().'_title'). '<br>';
        $msg.= '<a class="verify-code" href="' . $route. '">' . __('verify email') . '</a> </span>';

        $message = $msg;
        $subject =  __('Verify your email');

        try {
            Mail::to($request->email)->send(new BasicMail($message,$subject));
        }catch (\Exception $e){
            return redirect()->back()->with(ResponseMessage::delete($e->getMessage()));
        }

        return response()->success(__('Verify mail send success'));
    }
}
