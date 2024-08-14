<?php

namespace App\Http\Controllers\Landlord\Admin;

use App\Events\TenantRegisterEvent;
use App\Helpers\FlashMsg;
use App\Helpers\Payment\DatabaseUpdateAndMailSend\LandlordPricePlanAndTenantCreate;
use App\Helpers\TenantHelper\TenantHelpers;
use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Mail\OrderReply;
use App\Models\FormBuilder;
use App\Models\Language;
use App\Models\PaymentLogHistory;
use App\Models\PaymentLogs;
use App\Models\Tenant;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class OrderManageController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:package-order-all-order|package-order-pending-order|package-order-progress-order|
        package-order-edit|package-order-delete|package-order-complete-order|package-order-success-order-page|package-order-order-page-manage
        package-order-order-report');
    }

    private const ROOT_PATH = 'landlord.admin.package-order-manage.';

    public function all_orders(){
        $all_orders = PaymentLogs::latest()->get();
        return view(self::ROOT_PATH.'order-manage-all')->with(['all_orders' => $all_orders]);
    }

    public function view_order($tenant_id)
    {
        abort_if(empty($tenant_id), 404);

        $payment_log = PaymentLogs::where('tenant_id',$tenant_id)->latest()->first();

        if($payment_log->payment_status != 'complete'){
            $payment_history = PaymentLogs::where('tenant_id',$tenant_id)->paginate(5);
        }else{
            $payment_history = PaymentLogHistory::where('tenant_id',$tenant_id)->paginate(5);
        }

        $domain_name = $tenant_id.'.'.env('CENTRAL_DOMAIN');
        return view(self::ROOT_PATH.'order-view',compact('payment_history','domain_name'));
    }

    public function pending_orders(){
        $all_orders = PaymentLogs::where('status','pending')->get();
        return view(self::ROOT_PATH.'order-manage-pending')->with(['all_orders' => $all_orders]);
    }

    public function completed_orders(){
        $all_orders = PaymentLogs::where('status','complete')->orderBy('id','desc')->get();
        return view(self::ROOT_PATH.'order-manage-completed')->with(['all_orders' => $all_orders]);
    }
    public function in_progress_orders(){
        $all_orders = PaymentLogs::where('status','in_progress')->get();
        return view(self::ROOT_PATH.'order-manage-in-progress')->with(['all_orders' => $all_orders]);
    }



    public function order_reminder(Request $request){
        $order_details = PaymentLogs::find($request->id);
        $route = route('landlord.frontend.plan.order',optional($order_details->package)->id);
        //send order reminder mail
        $data['subject'] = __('your order is still in pending at').' '. get_static_option('site_'.get_default_language().'_title');
        $data['message'] = __('hello').' '.$order_details->name .'<br>';
        $data['message'] .= __('your order').' #'.$order_details->id.' ';
        $data['message'] .= __('is still in pending, to complete your order go to');
        $data['message'] .= ' <br> <br><a href="'.$route.'">'.__('go to payment ').'</a>';
        //send mail while order status change

        try {
            Mail::to($order_details->email)->send(new BasicMail($data['message'], $data['subject'] ));
        }catch(\Exception $e){
            return redirect()->back()->with(['type'=> 'danger', 'msg' => $e->getMessage()]);
        }

        return redirect()->back()->with(['msg' => __('PaymentLogs Reminder Mail Send Success...'),'type' => 'success']);
    }

    public function order_delete(Request $request,$id){

        $log = PaymentLogs::findOrFail($id);
        $user = \App\Models\User::findOrFail($log->user_id);

        if(!empty($user)){
            return redirect()->back()->with(['msg' => __('You cannot delete this item, This data is associated with a user, please delete the user then it will be deleted automatically..!'),'type' => 'danger']);
        }
        $log->delete();

        return redirect()->back()->with(['msg' => __('PaymentLogs Deleted Success...'),'type' => 'danger']);
    }


    public function send_mail(Request $request){
        $this->validate($request,[
            'email' => 'required|string|max:191',
            'name' => 'required|string|max:191',
            'subject' => 'required|string',
            'message' => 'required|string',
        ]);
        $subject = str_replace('{site}',get_static_option('site_'.get_default_language().'_title'),$request->subject);
        $data = [
            'name' => $request->name,
            'message' => $request->message,
        ];
        try{
          Mail::to($request->email)->send(new OrderReply($data,$subject));
        }catch(\Exception $e){
            return redirect()->back()->with(['type'=> 'danger', 'msg' => $e->getMessage()]);
        }
        return redirect()->back()->with(['msg' => __('Order Reply Mail Send Success...'),'type' => 'success']);
    }


    public function all_payment_logs(){
        $paymeng_logs = PaymentLogs::all();
        return view('landlord.admin.payment-logs.payment-logs-all')->with(['payment_logs' => $paymeng_logs]);
    }

    public function payment_logs_delete(Request $request,$id){
        PaymentLogs::find($id)->delete();
        return redirect()->back()->with(['msg' => __('Payment Log Delete Success...'),'type' => 'danger']);
    }

    public function change_status(Request $request) {

        dd("done");
        $this->validate($request,[
            'order_status' => 'required|string|max:191',
            'order_id' => 'required|string|max:191'
        ]);
        $order_details = PaymentLogs::with(['package','user'])->find($request->order_id);

        $tenantCreateHelper = TenantHelpers::init()
            ->setTenantId($order_details->tenant_id)
            ->setPaymentLog($order_details)
            ->setPackage($order_details->package)
            ->setUser($order_details->user)
            ->setTheme($order_details->theme);

        if($order_details->status == 'trial'){
            $tenantCreateHelper->paymentLogUpdate([
                'payment_status' => 'complete'
            ],true);
            return redirect()->back()->with(['msg' => __('Payment Status Changed Successfully..!'),'type' => 'success']);
        }

        $tenantCreateHelper->paymentLogUpdate([
            'payment_status' =>  $request->order_status == 'complete' ? 'complete' : $order_details->payment_status,
            'status' => $request->order_status
        ],true);
        $msg = __('Payment status changed successfully..!');

        if ($request->order_status == 'complete' && empty($order_details->tenant?->domain?->domain)) {

            try {
                /**
                 * @event TenantRegisterEvent
                 *
                 * @doing Follows
                 *
                 * Create Tenant
                 * Creating Domain
                 * Migrating Database
                 * Seeding Data
                 *
                 * */

                event(new TenantRegisterEvent(
                        $tenantCreateHelper->getUser(),
                        $tenantCreateHelper->getTenantId(),
                        $tenantCreateHelper->getTheme())
                );

                $tenantCreateHelper->sendCredentialsToTenant();

                $tenantCreateHelper->tenantUpdate([
                    'start_date' => $tenantCreateHelper->getStartDate(),
                    'expire_date' => $tenantCreateHelper->getExpiredDate(),
                    'user_id' => $tenantCreateHelper->getUser()->id,
                    'theme_slug' => $tenantCreateHelper->getPackage()->theme
                ],true);

                $msg .= ' '.__('And a new tenant has been created for the payment log');

                if(in_array($order_details->package_gateway,['manual_payment_','bank_transfer'])  && $order_details->payment_status == 'complete'){
                    //todo: update payment-log history using tenant helper file
                    $tenantCreateHelper->createPaymentLogHistory();
                }
            }catch (\Exception $e){
                $tenantCreateHelper->tenantExceptionCreate($e);
                return redirect()->back()->with(FlashMsg::item_delete(__('You have no permission to create database, an issue has been created, please go to website settings and manually generate this..!')));
            }

        }else if($request->order_status == 'cancel'){


            if($request->subscription_cancel_type == 'permanent_with_delete'){
                //todo: manage tenant delete from tenant helper

                try {
                    $tenantCreateHelper->deleteTenant();
                    return back()->with(['msg' => __('Tenant Deleted Successfully'),'type' => 'danger']);
                }catch (\Exception $e){
                    return back()->with(['msg' => $e->getMessage(),'type' => 'danger']);
                }
            }

            $expire_date_con = $request->subscription_cancel_type == 'temporary' ? $order_details->expire_date : Carbon::now()->subDay(1);
            $tenantCreateHelper->tenantUpdate([
                'start_date' => $order_details->start_date,
                'expire_date' => $expire_date_con,
                'user_id' => $order_details->user_id,
                'theme_slug' => $order_details->theme,
                'cancel_type' => $request->subscription_cancel_type
            ],true);

            $tenantCreateHelper->paymentLogUpdate([
                'status' => $request->order_status
            ],true);

        }else{

            $tenant = Tenant::find($order_details->tenant_id);
            if (is_null($tenant)){
                return redirect()->back()->with(['msg' => __("user website subdomain not found in database"),'type' => 'danger']);
            }

            $tenantCreateHelper->tenantUpdate([
                'start_date' => $order_details->start_date,
                'expire_date' => $order_details->expire_date,
                'user_id' => $order_details->user_id,
                'theme_slug' => $order_details->theme,
            ],true);
        }


        $tenantCreateHelper->sendOrderStatusChangeMailToTenant();
        return redirect()->back()->with(['msg' => $msg,'type' => 'success']);
    }


    public function payment_logs_approve($id){

        //todo:: work on payment logs approved
        $payment_logs = PaymentLogs::with(['package','user'])->find($id);

        $tenantHelper = TenantHelpers::init()
            ->setTenantId($payment_logs->tenant_id)
            ->setPackage($payment_logs->package)
            ->setPaymentLog($payment_logs)
            ->setUser($payment_logs->user)
            ->setTheme($payment_logs->theme);

        $order_details = $tenantHelper->getPackage();

        if($payment_logs->status == 'trial'){
            $tenantHelper->paymentLogUpdate([
                'payment_status' => 'complete'
            ],true);
            return redirect()->back()->with(['msg' => __('Payment Status Changed Successfully..!'),'type' => 'success']);
        }

        if ($payment_logs->is_renew == 1){
            $tenantHelper->setIsRenew(true);
        }

        $package_start_date = $tenantHelper->getStartDate();
        $package_expire_date = $tenantHelper->getExpiredDate();

        $tenantHelper->paymentLogUpdate([
            'payment_status' => 'complete',
            'status' => 'complete',
            'start_date' => $tenantHelper->getStartDate(),
            'expire_date' => $tenantHelper->getExpiredDate(),
        ],true);

        $lang = get_user_lang();

        if ($tenantHelper->getPaymentLog()->payment_status == 'complete')
        {
            try {
                //todo if tenant already exists
                //todo if database not exits create database
                //todo if domain not exits create domain


                $tenantHelper->sendCredentialsToTenant();

                LandlordPricePlanAndTenantCreate::tenant_create_event_with_credential_mail($tenantHelper->getPaymentLog()->id);

                $tenantHelper->tenantUpdate([
                    'start_date' => $tenantHelper->getStartDate(),
                    'expire_date' => $tenantHelper->getExpiredDate(),
                    'user_id' => $tenantHelper->getUser()->id,
                    'theme_slug' => $tenantHelper->getTheme()
                ],true);

                if(in_array($tenantHelper->getPaymentLog()->package_gateway,['manual_payment_','bank_transfer'])  && $tenantHelper->getPaymentLog()->payment_status == 'complete'){
                    //todo: update payment-log history using tenant helper file
                    $tenantHelper->createPaymentLogHistory();
                }

            }catch (\Exception $ex) {
             $message = $tenantHelper->tenantExceptionCreate($ex);
                return redirect()->back()->with(FlashMsg::item_delete($message));
            }
            $tenantHelper->sendNotificationEmailToUser($tenantHelper->getPaymentLog());
        }

        //todo send email to user about his subscription is approve
        try {
            $tenantHelper->sendSubscriptionApproveMailToUser($tenantHelper->getPaymentLog());
        }catch (\Exception $e){ }

        return redirect()->back()->with(['msg' => __('Order Accept Success'),'type' => 'success']);
    }

    public function order_success_payment(){
        $all_languages = Language::all();
        return view(self::ROOT_PATH.'order-success-page')->with(['all_languages' => $all_languages]);
    }

    public function update_order_success_payment(Request $request){

        $all_language = Language::all();
        foreach ($all_language as $lang) {
            $this->validate($request, [
                'site_order_success_page_' . $lang->slug . '_title' => 'nullable',
                'site_order_success_page_' . $lang->slug . '_description' => 'nullable',
            ]);
            $title = 'site_order_success_page_' . $lang->slug . '_title';
            $description = 'site_order_success_page_' . $lang->slug . '_description';

            update_static_option($title, $request->$title);
            update_static_option($description, $request->$description);
        }
        return redirect()->back()->with(['msg' => __('PaymentLogs Success Page Update Success...'),'type' => 'success']);
    }

    public function order_cancel_payment(){
        $all_languages = Language::all();
        return view(self::ROOT_PATH.'order-cancel-page')->with(['all_languages' => $all_languages]);
    }

    public function update_order_cancel_payment(Request $request){

        $all_language = Language::all();
        foreach ($all_language as $lang) {
            $this->validate($request, [
                'site_order_cancel_page_' . $lang->slug . '_title' => 'nullable',
                'site_order_cancel_page_' . $lang->slug . '_subtitle' => 'nullable',
                'site_order_cancel_page_' . $lang->slug . '_description' => 'nullable',
            ]);
            $title = 'site_order_cancel_page_' . $lang->slug . '_title';
            $subtitle = 'site_order_cancel_page_' . $lang->slug . '_subtitle';
            $description = 'site_order_cancel_page_' . $lang->slug . '_description';

            update_static_option($title, $request->$title);
            update_static_option($subtitle, $request->$subtitle);
            update_static_option($description, $request->$description);
        }
        return redirect()->back()->with(['msg' => __('PaymentLogs Cancel Page Update Success...'),'type' => 'success']);
    }

    public function bulk_action(Request $request){
        $all = PaymentLogs::find($request->ids);
        foreach($all as $item){
            $item->delete();
        }
        return response()->json(['status' => 'ok']);
    }

    public function payment_log_bulk_action(Request $request){
        $all = PaymentLogs::find($request->ids);
        foreach($all as $item){
            $item->delete();
        }
        return response()->json(['status' => 'ok']);
    }

    public function order_report(Request  $request){

        $order_data = '';
        $query = PaymentLogs::query();
        if (!empty($request->start_date)){
            $query->whereDate('created_at','>=',$request->start_date);
        }
        if (!empty($request->end_date)){
            $query->whereDate('created_at','<=',$request->end_date);
        }
        if (!empty($request->order_status)){
            $query->where(['status' => $request->order_status ]);
        }
        if (!empty($request->payment_status)){
            $query->where(['payment_status' => $request->payment_status ]);
        }
        $error_msg = __('select start & end date to generate order report');
        if (!empty($request->start_date) && !empty($request->end_date)){
            $query->orderBy('id','DESC');
            $order_data =  $query->paginate($request->items);
            $error_msg = '';
        }

        return view(self::ROOT_PATH.'order-report')->with([
            'order_data' => $order_data,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'items' => $request->items,
            'order_status' => $request->order_status,
            'payment_status' => $request->payment_status,
            'error_msg' => $error_msg
        ]);
    }

    public function payment_report(Request  $request){

        $order_data = '';
        $query = PaymentLogs::query();
        if (!empty($request->start_date)){
            $query->whereDate('created_at','>=',$request->start_date);
        }
        if (!empty($request->end_date)){
            $query->whereDate('created_at','<=',$request->end_date);
        }
        if (!empty($request->payment_status)){
            $query->where(['status' => $request->payment_status ]);
        }
        $error_msg = __('select start & end date to generate payment report');
        if (!empty($request->start_date) && !empty($request->end_date)){
            $query->orderBy('id','DESC');
            $order_data =  $query->paginate($request->items);
            $error_msg = '';
        }

        return view('landlord.admin.payment-logs.payment-report')->with([
            'order_data' => $order_data,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'items' => $request->items,
            'payment_status' => $request->payment_status,
            'error_msg' => $error_msg
        ]);
    }

    public function index(){
        $all_custom_form = FormBuilder::all();
        return view(self::ROOT_PATH.'form-section')->with(['all_custom_form' => $all_custom_form]);
    }
    public function udpate(Request $request){
        $this->validate($request,[
            'order_form' => 'nullable|string',
        ]);

        $all_language = Language::all();

        foreach ($all_language as $lang){

            $this->validate($request,[
                'order_page_'.$lang->slug.'_form_title' => 'nullable|string',
            ]);
            $field = 'order_page_'.$lang->slug.'_form_title';
            update_static_option('order_page_'.$lang->slug.'_form_title',$request->$field);
        }

        update_static_option('order_form',$request->order_form);

        return redirect()->back()->with(['msg' => __('Settings Updated....'),'type' => 'success']);
    }

    public function generate_package_invoice(Request $request)
    {
        $payment_details = PaymentLogs::find($request->id);
        if (empty($payment_details)) {
            return abort(404);
        }

        $pdf = PDF::loadview('landlord.frontend.invoice.package-order', ['payment_details' => $payment_details]) ->setOptions(['defaultFont' => 'sans-serif','isRemoteEnabled'=>true]);;

        $pdf->setPaper('L');
        $pdf->output();
        $canvas = $pdf->getDomPDF()->getCanvas();

        $height = $canvas->get_height();
        $width = $canvas->get_width();

        $canvas->set_opacity(.2,"Multiply");
        $canvas->set_opacity(.2);
        $canvas->page_text($width/5, $height/2, __('Paid'), null, 55, array(0,0,0),2,2,-30);

        return $pdf->download('package-invoice.pdf');
    }



}
