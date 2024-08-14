<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Events\TenantRegisterEvent;
use App\Helpers\ResponseMessage;
use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Mail\PlaceOrder;
use App\Mail\TenantCredentialMail;
use App\Models\PaymentLogs;
use App\Models\PricePlan;
use App\Models\Tenant;
use App\Models\User;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class MyPackageOrderController extends Controller
{
    public function my_payment_logs(){
        $package_orders = tenant()->user()->first()->payment_log()->where('tenant_id',tenant()->id)->get();
        $current_package = tenant()->user()->first()->payment_log()->first();
        return view('tenant.admin.my-orders.package-order')->with(['package_orders' => $package_orders, 'current_package'=> $current_package]);
    }

    public function generate_package_invoice(Request $request)
    {
        $user = tenant()->user()->first()->payment_log();
        $payment_details = $user->where('id',$request->id)->first();

        if (empty($payment_details)) {
            return abort(404);
        }


        $pdf = PDF::loadview('landlord.frontend.invoice.package-order', ['payment_details' => $payment_details])->setOptions(['defaultFont' => 'sans-serif']);
        return $pdf->download('package-invoice.pdf');
    }

    public function package_order_cancel($id){
        abort_if(empty($id),404);

        $order_mail =  get_static_option_central('site_global_email');
        $order_details = PaymentLogs::find($id);
        $order_details->status = 'cancel';
        $order_details->save();

        //send mail to admin
        $data['subject'] = __('one of your package order has been cancelled');
        $data['message'] = __('hello') . '<br>';
        $data['message'] .= __('your package order ') . ' #' . $order_details->id . ' ';
        $data['message'] .= __('has been cancelled by the user');

        //send mail while order status change
        try {
            Mail::to($order_mail)->send(new BasicMail($data['message'],$data['subject']));

        } catch (\Exception $e) {
            //handle error
            return redirect()->back()->with(['msg' => __('Order Cancel, mail send failed'), 'type' => 'warning']);
        }


        //user Mail send
        if (!empty($order_details)) {
            //send mail to customer
            $data['subject'] = __('your order status has been cancel');
            $data['message'] = __('hello') . '<br>';
            $data['message'] .= __('your order') . ' #' . $order_details->id . ' ';
            $data['message'] .= __('status has been changed to cancel');
            try {
                //send mail while order status change
                Mail::to($order_details->email)->send(new BasicMail($data['message'],$data['subject']));
            } catch (\Exception $e) {
                //handle error
                return redirect()->back()->with(['msg' => __('Order Cancel, mail send failed'), 'type' => 'warning']);
            }

        }
        return redirect()->back()->with(['msg' => __('Subscription Canceled Successfully..!'), 'type' => 'success']);
    }


    public function package_renew_process(Request $request){

        $package_id = $request->package_id;
        $log_id = $request->log_id;
        $payment_gateway = $request->payment_gateway;
        $manual_payment_attachment = $request->manual_payment_attachment;
        $transaction_id = $request->transaction_id;

        $manual_attachment_condition = '';

        if($payment_gateway == 'bank_transfer'){

            $request->validate([
                'manual_payment_attachment' => 'required'
            ]);

            $tenant_id = $request->tenant_id;
            $only_path = 'assets/uploads/renew-tenant-attachment/';
            $fileName = $tenant_id. '.'. $manual_payment_attachment->extension();
            $saving_path_with_file = 'assets/uploads/renew-tenant-attachment/'.$fileName;

            $manual_attachment_condition = !is_null($manual_payment_attachment) ? $fileName : '';

            if(file_exists($saving_path_with_file) && !is_dir($saving_path_with_file)){
                unlink($saving_path_with_file);
            }

            if(!file_exists($saving_path_with_file) && !is_dir($saving_path_with_file)){
                $manual_payment_attachment->move($only_path,$fileName);
            }

        }else if ($payment_gateway == 'manual_payment_'){

            $request->validate([
                'transaction_id' => 'required'
            ]);

            $transaction_id = $transaction_id;
        }


        return redirect(route('landlord.frontend.plan.order',$package_id).
            '?log_id='.$log_id.'&gateway='.$payment_gateway.''.'&manual_attachment='.$manual_attachment_condition.''.'&transaction_id='.$transaction_id.'');
    }


    public function package_payment_history($tenant_id)
    {
        abort_if(empty($tenant_id), 404);
        $package_orders = tenant()->payment_log_history()->where('tenant_id',$tenant_id)->get();
        $domain_name = $tenant_id . '.' .env('CENTRAL_DOMAIN');

        return view('tenant.admin.my-orders.package-order-history')->with(['package_orders' => $package_orders, 'domain_name'=> $domain_name]);
    }



}
