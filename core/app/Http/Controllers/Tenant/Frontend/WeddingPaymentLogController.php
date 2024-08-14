<?php

namespace App\Http\Controllers\Tenant\Frontend;

use App\Helpers\FlashMsg;
use App\Helpers\Payment\DatabaseUpdateAndMailSend\Tenant\TenantWedding;
use App\Helpers\Payment\PaymentGatewayCredential;
use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Mail\EventMail;
use App\Mail\WeddingMail;
use App\Models\PaymentLogs;
use App\Models\WeddingPaymentLog;
use App\Models\WeddingPricePlan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Modules\Event\Entities\EventPaymentLog;

class WeddingPaymentLogController extends Controller
{
    private const SUCCESS_ROUTE = 'tenant.frontend.wedding.success.plan.order';
    private const STATIC_CANCEL_ROUTE = 'tenant.frontend.wedding.cancel.plan.order';

    private static function go_home_page()
    {
        return redirect()->route('tenant.frontend.homepage');
    }

    public function order_payment_form(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'package_id' => 'required|string',
            'payment_gateway' => 'nullable',
        ]);

        $order_details = WeddingPricePlan::find($request->package_id) ?? [];
        $amount_to_charge = $order_details->price;
        $selected_payment_gateway = $request->payment_gateway ?? $request->selected_payment_gateway;

        $auth_id = auth()->guard('web')->user()->id;

        DB::beginTransaction(); // Starting all the actions as safe transactions

        try {
            $payment_details = WeddingPaymentLog::create([
                'package_id' => $order_details->id,
                'user_id' => $auth_id,
                'name' => $request->name,
                'email' => $request->email,
                'package_name' => $order_details->getTranslation('title',get_user_lang()),
                'package_price' => $amount_to_charge,
                'package_gateway' => $selected_payment_gateway,
                'status' => 'pending',
                'payment_status' => 'pending',
                'transaction_id' => 'pending',
                'track' => Str::random(10) . Str::random(10),
            ]);

            DB::commit();

        } catch (\Exception $exception) {

            DB::rollBack(); // Rollback all the actions
            return back()->with(FlashMsg::create_failed($exception->getMessage()));
        }

        if ($selected_payment_gateway === 'paypal') {

            $params = $this->common_charge_customer_data($amount_to_charge,$payment_details,$request,route('tenant.wedding.frontend.paypal.ipn'));
            $paypal = PaymentGatewayCredential::get_paypal_credential();
            return $paypal->charge_customer($params);

        } elseif ($selected_payment_gateway === 'paytm') {

            $params = $this->common_charge_customer_data($amount_to_charge,$payment_details,$request,route('tenant.wedding.frontend.paytm.ipn'));
            $paytm = PaymentGatewayCredential::get_paytm_credential();
            return $paytm->charge_customer($params);

        } elseif ($selected_payment_gateway === 'mollie') {

            $params = $this->common_charge_customer_data($amount_to_charge,$payment_details,$request,route('tenant.wedding.frontend.mollie.ipn'));
            $mollie = PaymentGatewayCredential::get_mollie_credential();
            return $mollie->charge_customer($params);

        } elseif ($selected_payment_gateway === 'stripe') {

            $params = $this->common_charge_customer_data($amount_to_charge,$payment_details,$request,route('tenant.wedding.frontend.stripe.ipn'));

            $stripe = PaymentGatewayCredential::get_stripe_credential();
            return $stripe->charge_customer($params);

        } elseif ($selected_payment_gateway === 'razorpay') {

            $params = $this->common_charge_customer_data($amount_to_charge,$payment_details,$request,route('tenant.wedding.frontend.razorpay.ipn'));
            $razorpay = PaymentGatewayCredential::get_razorpay_credential();
            return $razorpay->charge_customer($params);

        } elseif ($selected_payment_gateway === 'flutterwave') {

            $params = $this->common_charge_customer_data($amount_to_charge,$payment_details,$request,route('tenant.wedding.frontend.flutterwave.ipn'));
            $flutterwave = PaymentGatewayCredential::get_flutterwave_credential();
            return $flutterwave->charge_customer($params);

        } elseif ($selected_payment_gateway === 'paystack') {

            $params = $this->common_charge_customer_data($amount_to_charge,$payment_details,$request,route('tenant.wedding.frontend.paystack.ipn'));
            $paystack = PaymentGatewayCredential::get_paystack_credential();
            return $paystack->charge_customer($params);

        } elseif ($selected_payment_gateway === 'midtrans') {

            $params = $this->common_charge_customer_data($amount_to_charge,$payment_details,$request,route('tenant.wedding.frontend.midtrans.ipn'));
            $midtrans = PaymentGatewayCredential::get_midtrans_credential();
            return $midtrans->charge_customer($params);

        } elseif ($selected_payment_gateway == 'payfast') {

            $params = $this->common_charge_customer_data($amount_to_charge,$payment_details,$request,route('tenant.wedding.frontend.payfast.ipn'));
            $payfast = PaymentGatewayCredential::get_payfast_credential();
            return $payfast->charge_customer($params);

        } elseif ($selected_payment_gateway == 'cashfree') {

            $params = $this->common_charge_customer_data($amount_to_charge,$payment_details,$request,route('tenant.wedding.frontend.cashfree.ipn'));
            $cashfree = PaymentGatewayCredential::get_cashfree_credential();
            return $cashfree->charge_customer($params);

        } elseif ($selected_payment_gateway == 'instamojo') {

            $params = $this->common_charge_customer_data($amount_to_charge,$payment_details,$request,route('tenant.wedding.frontend.instamojo.ipn'));
            $instamojo = PaymentGatewayCredential::get_instamojo_credential();
            return $instamojo->charge_customer($params);

        } elseif ($selected_payment_gateway == 'marcadopago') {

            $params = $this->common_charge_customer_data($amount_to_charge,$payment_details,$request,route('tenant.wedding.frontend.marcadopago.ipn'));
            $marcadopago = PaymentGatewayCredential::get_marcadopago_credential();
            return $marcadopago->charge_customer($params);

        }
        elseif($selected_payment_gateway == 'squareup')
        {
            $params = $this->common_charge_customer_data($amount_to_charge,$payment_details,$request,route('tenant.wedding.frontend.squareup.ipn'));
            $squareup = PaymentGatewayCredential::get_squareup_credential();
            return $squareup->charge_customer($params);
        }

        elseif($selected_payment_gateway == 'cinetpay')
        {
            $params = $this->common_charge_customer_data($amount_to_charge,$payment_details,$request,route('tenant.wedding.frontend.cinetpay.ipn'));
            $cinetpay = PaymentGatewayCredential::get_cinetpay_credential();
            return $cinetpay->charge_customer($params);
        }

        elseif($selected_payment_gateway == 'paytabs')
        {
            $params = $this->common_charge_customer_data($amount_to_charge,$payment_details,$request,route('tenant.wedding.frontend.paytabs.ipn'));
            $paytabs = PaymentGatewayCredential::get_paytabs_credential();
            return $paytabs->charge_customer($params ?? []);
        }
        elseif($selected_payment_gateway == 'billplz')
        {
            $params = $this->common_charge_customer_data($amount_to_charge,$payment_details,$request,route('tenant.wedding.frontend.billplz.ipn'));
            $billplz = PaymentGatewayCredential::get_billplz_credential();
            return $billplz->charge_customer($params);
        }
        elseif($selected_payment_gateway == 'zitopay')
        {
            $params = $this->common_charge_customer_data($amount_to_charge,$payment_details,$request,route('tenant.wedding.frontend.zitopay.ipn'));
            $zitopay = PaymentGatewayCredential::get_zitopay_credential();
            return $zitopay->charge_customer($params);
        }

        elseif($selected_payment_gateway == 'toyyibpay')
        {
            $params = $this->common_charge_customer_data($amount_to_charge,$payment_details,$request,route('tenant.wedding.frontend.toyyibpay.ipn'));
            $toyyibpay = PaymentGatewayCredential::get_toyyibpay_credential();
            return $toyyibpay->charge_customer($params);
        }
        elseif($selected_payment_gateway == 'pagali')
        {
            $params = $this->common_charge_customer_data($amount_to_charge,$payment_details,$request,route('tenant.wedding.frontend.pagali.ipn'));
            $pagali = PaymentGatewayCredential::get_pagali_credential();
            return $pagali->charge_customer($params);
        }
        elseif($selected_payment_gateway == 'authorizenet')
        {
            $params = $this->common_charge_customer_data($amount_to_charge,$payment_details,$request,route('tenant.wedding.frontend.authorizenet.ipn'));
            $authorizenet = PaymentGatewayCredential::get_authorizenet_credential();
            return $authorizenet->charge_customer($params);
        }
        elseif($selected_payment_gateway == 'sitesway')
        {
            $params = $this->common_charge_customer_data($amount_to_charge,$payment_details,$request,route('tenant.wedding.frontend.sitesway.ipn'));
            $sitesway = PaymentGatewayCredential::get_sitesway_credential();

            return $sitesway->charge_customer($params);
        }

        elseif($selected_payment_gateway == 'kinetic')
        {
            $params = $this->common_charge_customer_data($amount_to_charge,$payment_details,$request,route('tenant.wedding.frontend.kinetic.ipn'));
            $kinetic = PaymentGatewayCredential::get_kinetic_credential();
//            dd($params,$request->all());
            return $kinetic->charge_customer($params);
        }

        elseif ($selected_payment_gateway == 'bank_transfer')
        {
                $this->validate($request, [
                    'manual_payment_attachment' => 'required'
                ], ['manual_payment_attachment.required' => __('Bank Attachment Required')]);

                $fileName = time().'.'.$request->manual_payment_attachment->extension();
                $request->manual_payment_attachment->move('assets/uploads/attachment/',$fileName);

                WeddingPaymentLog::where('id', $payment_details->id)->update([
                    'manual_payment_attachment' => $fileName,
                    'status' => 'pending',
                    'payment_status' => 'pending'
                ]);

            $customer_subject = __('Your price plan payment sent and it is in admin approval stage..!').' '.get_static_option('site_'.get_user_lang().'_title');
            $admin_subject = __('You have a new price plan with bank transfer, please check and approve..!').' '.get_static_option('site_'.get_user_lang().'_title');

            try {
                Mail::to(get_static_option('tenant_site_global_email'))->send(new WeddingMail($payment_details, $admin_subject,"admin"));
                Mail::to($payment_details->email)->send(new WeddingMail( $payment_details, $customer_subject,'user'));

            } catch (\Exception $e) {
                return redirect()->back()->with(['type' => 'danger', 'msg' => $e->getMessage()]);
            }


            $order_id = Str::random(6) .$payment_details->id . Str::random(6);
            return redirect()->route(self::SUCCESS_ROUTE,$order_id);

        }else if($selected_payment_gateway == 'manual_payment_') {

            $this->validate($request, [
                'transaction_id' => 'required'
            ]);

            $customer_subject = __('Your price plan payment sent and it is in admin approval stage..!').' '.get_static_option('site_'.get_user_lang().'_title');
            $admin_subject = __('You have a new price plan with bank transfer, please check and approve..!').' '.get_static_option('site_'.get_user_lang().'_title');

            try {
                Mail::to(get_static_option('tenant_site_global_email'))->send(new WeddingMail($payment_details, $admin_subject,"admin"));
                Mail::to($payment_details->email)->send(new WeddingMail( $payment_details, $customer_subject,'user'));

            } catch (\Exception $e) {
                return redirect()->back()->with(['type' => 'danger', 'msg' => $e->getMessage()]);
            }

            WeddingPaymentLog::where('id', $payment_details->id)->update([
                'transaction_id' => $request->transaction_id,
                'status' => 'pending',
                'payment_status' => 'pending'
            ]);

            $order_id = Str::random(6) .$payment_details->id . Str::random(6);
            return redirect()->route(self::SUCCESS_ROUTE,$order_id);

        }else{
            return $this->payment_with_gateway($selected_payment_gateway, $amount_to_charge,$payment_details,$request);
        }
        return redirect()->route('tenant.frontend.homepage');
    }

    public function payment_with_gateway($selected_payment_gateway, $amount_to_charge, $payment_details, $request)
    {

        $gateway_function = 'get_' . $selected_payment_gateway . '_credential';

        if (!method_exists((new PaymentGatewayCredential()), $gateway_function))
        {
            $custom_data['request'] = $request;
            $custom_data['payment_details'] = $payment_details->toArray();
            $custom_data['total'] = $payment_details->package_price;

            //add extra param support to the shop checkout payment system
            $custom_data['payment_type'] = "wedding_price_plan";
            $custom_data['payment_for'] = "tenant";
            $custom_data['cancel_url'] = route(self::STATIC_CANCEL_ROUTE);
            $custom_data['success_url'] = route(self::SUCCESS_ROUTE, random_int(111111,999999) . $payment_details->id . random_int(111111,999999));

            $charge_customer_class_namespace = getChargeCustomerMethodNameByPaymentGatewayNameSpace($selected_payment_gateway);
            $charge_customer_method_name = getChargeCustomerMethodNameByPaymentGatewayName($selected_payment_gateway);

            $custom_charge_customer_class_object = new $charge_customer_class_namespace;
            if(class_exists($charge_customer_class_namespace) && method_exists($custom_charge_customer_class_object, $charge_customer_method_name))
            {
                try {
                    return $custom_charge_customer_class_object->$charge_customer_method_name($custom_data);
                }catch (\Exception $e){
                    return back()->with(FlashMsg::explain('danger', $e->getMessage()));
                }
            } else {
                return back()->with(FlashMsg::explain('danger', 'Incorrect Class or Method'));
            }
        } else {

            try {
                $gateway = PaymentGatewayCredential::$gateway_function();
                $params = $this->common_charge_customer_data(
                    $amount_to_charge,
                    $payment_details,
                    $request,
                    route('tenant.wedding.frontend.'.$selected_payment_gateway.'.ipn')
                );
                return $gateway->charge_customer($params);
            } catch (\Exception $e) {
                return back()->with(['msg' => $e->getMessage(), 'type' => 'danger']);
            }


        }


    }

    public function paypal_ipn()
    {
        $paypal = PaymentGatewayCredential::get_paypal_credential();
        try{
            $payment_data = $paypal->ipn_response();
             return $this->common_ipn_data($payment_data);
        }catch(\Exception $e){
           return self::go_home_page();
        }

    }

    public function paytm_ipn()
    {
        $paytm = PaymentGatewayCredential::get_paytm_credential();

        try{
            $payment_data = $paytm->ipn_response();
             return $this->common_ipn_data($payment_data);
        }catch(\Exception $e){
           return self::go_home_page();
        }
    }

    public function flutterwave_ipn()
    {
        $flutterwave = PaymentGatewayCredential::get_flutterwave_credential();

        try{
            $payment_data = $flutterwave->ipn_response();
              return $this->common_ipn_data($payment_data);
        }catch(\Exception $e){
           return self::go_home_page();
        }
    }

    public function stripe_ipn()
    {
        $stripe = PaymentGatewayCredential::get_stripe_credential();
        try{
            $payment_data = $stripe->ipn_response();
            return $this->common_ipn_data($payment_data);
        }catch(\Exception $e){
            return self::go_home_page();
        }
    }

    public function razorpay_ipn()
    {
        $razorpay = PaymentGatewayCredential::get_razorpay_credential();
        try{
            $payment_data = $razorpay->ipn_response();
              return $this->common_ipn_data($payment_data);
        }catch(\Exception $e){
           return self::go_home_page();
        }
    }

    public function paystack_ipn()
    {
        $paystack = PaymentGatewayCredential::get_paystack_credential();
        try{
            $payment_data = $paystack->ipn_response();
            return $this->common_ipn_data($payment_data);
        }catch(\Exception $e){
           return self::go_home_page();
        }

    }

    public function payfast_ipn()
    {
        $payfast = PaymentGatewayCredential::get_payfast_credential();
        try{
            $payment_data = $payfast->ipn_response();
             return $this->common_ipn_data($payment_data);
        }catch(\Exception $e){
           return self::go_home_page();
        }
    }

    public function mollie_ipn()
    {
        $mollie = PaymentGatewayCredential::get_mollie_credential();
        try{
            $payment_data = $mollie->ipn_response();
            return $this->common_ipn_data($payment_data);
        }catch(\Exception $e){
           return self::go_home_page();
        }
    }

    public function midtrans_ipn()
    {
        $midtrans = PaymentGatewayCredential::get_midtrans_credential();
        try{
            $payment_data = $midtrans->ipn_response();
             return $this->common_ipn_data($payment_data);
        }catch(\Exception $e){
           return self::go_home_page();
        }

    }

    public function cashfree_ipn()
    {
        $cashfree = PaymentGatewayCredential::get_cashfree_credential();
        try{
            $payment_data = $cashfree->ipn_response();
             return $this->common_ipn_data($payment_data);

        }catch(\Exception $e){
            return self::go_home_page();
        }

    }

    public function instamojo_ipn()
    {
        $instamojo = PaymentGatewayCredential::get_instamojo_credential();
        try{
            $payment_data = $instamojo->ipn_response();
            return $this->common_ipn_data($payment_data);
        }catch(\Exception $e){
            return self::go_home_page();
        }
    }

    public function marcadopago_ipn()
    {
        $marcadopago = PaymentGatewayCredential::get_marcadopago_credential();
        try{
            $payment_data = $marcadopago->ipn_response();
             return $this->common_ipn_data($payment_data);

        }catch(\Exception $e){
           return self::go_home_page();
        }

    }

    public function squareup_ipn()
    {
        $squareup = PaymentGatewayCredential::get_squareup_credential();
        try{
            $payment_data = $squareup->ipn_response();
             return $this->common_ipn_data($payment_data);
        }catch(\Exception $e){
          return self::go_home_page();
        }
    }

    public function cinetpay_ipn()
    {
        $cinetpay = PaymentGatewayCredential::get_cinetpay_credential();
        try{
            $payment_data = $cinetpay->ipn_response();
             return $this->common_ipn_data($payment_data);
        }catch(\Exception $e){
            return self::go_home_page();
        }
    }

    public function paytabs_ipn()
    {
        $paytabs = PaymentGatewayCredential::get_paytabs_credential();

        try{
            $payment_data = $paytabs->ipn_response();
            return $this->common_ipn_data($payment_data);
        }catch(\Exception $e){
            return self::go_home_page();
        }

    }

    public function billplz_ipn()
    {
        $billplz = PaymentGatewayCredential::get_billplz_credential();

        try{
            $payment_data = $billplz->ipn_response();
            return $this->common_ipn_data($payment_data);
        }catch(\Exception $e){
           return self::go_home_page();
        }


    }

    public function zitopay_ipn()
    {
        $zitopay = PaymentGatewayCredential::get_zitopay_credential();
        try{
            $payment_data = $zitopay->ipn_response();
            return $this->common_ipn_data($payment_data);
        }catch(\Exception $e){
           return self::go_home_page();
        }

    }

    public function toyyibpay_ipn()
    {
        $toyyibpay = PaymentGatewayCredential::get_toyyibpay_credential();
        $payment_data = $toyyibpay->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function pagali_ipn()
    {
        $pagali_ipn = PaymentGatewayCredential::get_pagali_credential();
        $payment_data = $pagali_ipn->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function authorizenet_ipn()
    {
        $authorize_ipn = PaymentGatewayCredential::get_authorizenet_credential();
        $payment_data = $authorize_ipn->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function sitesway_ipn()
    {
        $sitesway_ipn = PaymentGatewayCredential::get_sitesway_credential();
        $payment_data = $sitesway_ipn->ipn_response();
        return $this->common_ipn_data($payment_data);
    }


    private function common_charge_customer_data($amount_to_charge,$payment_details,$request,$ipn_url) : array
    {
        $data = [
            'amount' => $amount_to_charge ,
            'title' => $payment_details->package_name,
            'description' => 'Payment For Package Order Id: #' . $request->package_id . ' Package Name: ' . $payment_details->package_name .
                'Payer Name: ' . $request->name . ' Payer Email:' . $request->email,
            'order_id' => $payment_details->id,
            'track' => $payment_details->track,
            'cancel_url' => route(self::STATIC_CANCEL_ROUTE),
            'success_url' => route(self::SUCCESS_ROUTE, $payment_details->id),
            'email' => $payment_details->email,
            'name' => $payment_details->name,
            'payment_type' => 'wedding plan order',
            'ipn_url' => $ipn_url,
        ];

        return $data;
    }

    private function common_ipn_data($payment_data)
    {
        if (isset($payment_data['status']) && $payment_data['status'] === 'complete') {

            try{
                TenantWedding::update_database($payment_data['order_id'], $payment_data['transaction_id']);
                TenantWedding::send_wedding_order_mail($payment_data['order_id']);

            }catch(\Exception $e){

            }

            $order_id = wrap_random_number($payment_data['order_id']);
            return redirect()->route(self::SUCCESS_ROUTE, $order_id);
        }

        return redirect()->route(self::STATIC_CANCEL_ROUTE);
    }

}
