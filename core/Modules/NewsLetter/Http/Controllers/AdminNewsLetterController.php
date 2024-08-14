<?php

namespace Modules\NewsLetter\Http\Controllers;

use App\Mail\BasicMail;
use App\Mail\SubscriberMessage;
use Modules\NewsLetter\Entities\NewsLetter;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Modules\NewsLetter\Http\Requests\AdminSendAllEmailRequest;
use Modules\NewsLetter\Http\Requests\AdminSendMailReqeuest;

class AdminNewsLetterController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
        $this->middleware('permission:newsletter-list|newsletter-create|newsletter-mail-send|newsletter-delete',['only' => ['index']]);
        $this->middleware('permission:newsletter-create',['only' => ['add_new_sub']]);
        $this->middleware('permission:newsletter-mail-send',['only' => ['send_mail_all_index','send_mail_all','verify_mail_send']]);
        $this->middleware('permission:newsletter-delete',['only' => ['delete','bulk_action']]);
    }

    public function index(){
        $all_subscriber = Newsletter::all();

        return view('newsletter::admin.newsletter-index')->with(['all_subscriber' => $all_subscriber]);
    }

    public function send_mail(AdminSendMailReqeuest $request){
        // validation are done by request
        $data = $request->validated();

        try {
            Mail::to($request->email)->send(new SubscriberMessage($data));
        } catch (\Throwable $th) {
        }

        return redirect()->back()->with([
            'msg' => __('Mail Send Success...'),
            'type' => 'success'
        ]);
    }
    public function delete($id){
        Newsletter::find($id)->delete();
        return redirect()->back()->with(['msg' => __('Subscriber Delete Success....'),'type' => 'danger']);
    }

    public function send_mail_all_index(){
        return view('newsletter::admin.send-main-to-all');
    }

    public function send_mail_all(AdminSendAllEmailRequest $request){
        $all_subscriber = Newsletter::all();

        foreach ($all_subscriber as $subscriber){
            $data = [
                'subject' => $request->subject,
                'message' => $request->message,
            ];

            try {
                Mail::to($subscriber->email)->send(new SubscriberMessage($data));
            } catch (\Throwable $th) {
            }
        }

        return redirect()->back()->with([
            'msg' => __('Mail Send Success..'),
            'type' => 'success'
        ]);
    }

    public function add_new_sub(Request $request){
        $this->validate($request,[
            'email' => 'required|email|unique:newsletters'
        ],
            [
                'email.required' => __('email field required')
            ]);

        Newsletter::create($request->all());
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
        $message = __('Verify your email to get all news from '). get_static_option('site_title') . '<div class="btn-wrap"> <a class="anchor-btn" href="' . route('subscriber.verify', ['token' => $token]) . '">' . __('verify email') . '</a></div>';
        $data = [
            'message' => $message,
            'subject' => __('verify your email')
        ];

        try {
            //send verify mail to newsletter subscriber
            Mail::to($subscriber_details->email)->send(new BasicMail($data));
        } catch (\Throwable $th) {
        }

        return redirect()->back()->with([
            'msg' => __('verify mail send success'),
            'type' => 'success'
        ]);
    }
}
