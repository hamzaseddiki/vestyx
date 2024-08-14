<?php

namespace Modules\Event\Http\Controllers\Tenant\Frontend;
use App\Helpers\FlashMsg;
use App\Helpers\Payment\DatabaseUpdateAndMailSend\Tenant\TenantEvent;
use App\Helpers\Payment\PaymentGatewayCredential;
use App\Http\Controllers\Controller;
use App\Mail\EventMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Modules\Event\Entities\Event;
use Modules\Event\Entities\EventPaymentLog;

class EventPaymentLogController extends Controller
{
    private const SUCCESS_ROUTE = 'tenant.frontend.event.payment.success';
    private const CANCEL_ROUTE = 'tenant.frontend.event.payment.cancel';

    public function event_payment_form(Request $request)
    {

         $request->validate([
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'phone' => 'required',
        ]);

        $event = Event::find($request->event_id);
        $auth_user  = auth()->guard('web')->user();
        $selected_payment_gateway = $request->selected_payment_gateway;
        $amount_to_charge = $request->event_total_amount;
        try {

            $payment_details = EventPaymentLog::create([
                'event_id' => $event->id,
                'user_id' => $auth_user->id ?? null,
                'name' => $request->name,
                'email' => $request->email,
                'phone' =>$request->phone,
                'address' => $request->address,
                'amount' => $request->event_total_amount,
                'ticket_qty' => $request->event_total_ticket_qty,
                'status' => 0,
                'payment_gateway' => $selected_payment_gateway,
                'track' => Str::random(10) . Str::random(10),
            ]);

        }catch (\Exception $ex){
            return redirect()->back()->with(['msg'=> $ex->getMessage(), 'type' => 'danger']);
        }

        if ($selected_payment_gateway === 'paypal') {

            $params = $this->common_charge_customer_data($amount_to_charge,$payment_details,$request,route('tenant.frontend.event.paypal.ipn'));
            $paypal = PaymentGatewayCredential::get_paypal_credential();
            return $paypal->charge_customer($params);

        } elseif ($selected_payment_gateway === 'paytm') {

            $params = $this->common_charge_customer_data($amount_to_charge,$payment_details,$request,route('tenant.frontend.event.paytm.ipn'));
            $paytm = PaymentGatewayCredential::get_paytm_credential();
            return $paytm->charge_customer($params);

        } elseif ($selected_payment_gateway === 'mollie') {

            $params = $this->common_charge_customer_data($amount_to_charge,$payment_details,$request,route('tenant.frontend.event.mollie.ipn'));
            $mollie = PaymentGatewayCredential::get_mollie_credential();
            return $mollie->charge_customer($params);

        } elseif ($selected_payment_gateway === 'stripe') {

            $params = $this->common_charge_customer_data($amount_to_charge,$payment_details,$request,route('tenant.frontend.event.stripe.ipn'));
            $stripe = PaymentGatewayCredential::get_stripe_credential();
            return $stripe->charge_customer($params);

        } elseif ($selected_payment_gateway === 'razorpay') {

            $params = $this->common_charge_customer_data($amount_to_charge,$payment_details,$request,route('tenant.frontend.event.razorpay.ipn'));
            $razorpay = PaymentGatewayCredential::get_razorpay_credential();
            return $razorpay->charge_customer($params);

        } elseif ($selected_payment_gateway === 'flutterwave') {

            $params = $this->common_charge_customer_data($amount_to_charge,$payment_details,$request,route('tenant.frontend.event.flutterwave.ipn'));
            $flutterwave = PaymentGatewayCredential::get_flutterwave_credential();
            return $flutterwave->charge_customer($params);

        } elseif ($selected_payment_gateway === 'paystack') {

            $params = $this->common_charge_customer_data($amount_to_charge,$payment_details,$request,route('tenant.frontend.event.paystack.ipn'));
            $paystack = PaymentGatewayCredential::get_paystack_credential();
            return $paystack->charge_customer($params);

        } elseif ($selected_payment_gateway === 'midtrans') {

            $params = $this->common_charge_customer_data($amount_to_charge,$payment_details,$request,route('tenant.frontend.event.midtrans.ipn'));
            $midtrans = PaymentGatewayCredential::get_midtrans_credential();
            return $midtrans->charge_customer($params);

        } elseif ($selected_payment_gateway == 'payfast') {

            $params = $this->common_charge_customer_data($amount_to_charge,$payment_details,$request,route('tenant.frontend.event.payfast.ipn'));
            $payfast = PaymentGatewayCredential::get_payfast_credential();
            return $payfast->charge_customer($params);

        } elseif ($selected_payment_gateway == 'cashfree') {

            $params = $this->common_charge_customer_data($amount_to_charge,$payment_details,$request,route('tenant.frontend.event.cashfree.ipn'));
            $cashfree = PaymentGatewayCredential::get_cashfree_credential();
            return $cashfree->charge_customer($params);

        } elseif ($selected_payment_gateway == 'instamojo') {

            $params = $this->common_charge_customer_data($amount_to_charge,$payment_details,$request,route('tenant.frontend.event.instamojo.ipn'));
            $instamojo = PaymentGatewayCredential::get_instamojo_credential();
           return $instamojo->charge_customer($params);

        } elseif ($selected_payment_gateway == 'marcadopago') {

            $params = $this->common_charge_customer_data($amount_to_charge,$payment_details,$request,route('tenant.frontend.event.marcadopago.ipn'));
            $marcadopago = PaymentGatewayCredential::get_marcadopago_credential();
            return $marcadopago->charge_customer($params);

        }
        elseif($selected_payment_gateway == 'squareup')
        {
            $params = $this->common_charge_customer_data($amount_to_charge,$payment_details,$request,route('tenant.frontend.event.squareup.ipn'));
            $squareup = PaymentGatewayCredential::get_squareup_credential();
            return $squareup->charge_customer($params);
        }

        elseif($selected_payment_gateway == 'cinetpay')
        {
            $params = $this->common_charge_customer_data($amount_to_charge,$payment_details,$request,route('tenant.frontend.event.cinetpay.ipn'));
            $cinetpay = PaymentGatewayCredential::get_cinetpay_credential();
            return $cinetpay->charge_customer($params);
        }

        elseif($selected_payment_gateway == 'pay_tabs')
        {
            $params = $this->common_charge_customer_data($amount_to_charge,$payment_details,$request,route('tenant.frontend.event.paytabs.ipn'));
            $paytabs = PaymentGatewayCredential::get_paytabs_credential();
             return $paytabs->charge_customer($params);
        }
        elseif($selected_payment_gateway == 'billplz')
        {
            $params = $this->common_charge_customer_data($amount_to_charge,$payment_details,$request,route('tenant.frontend.event.billplz.ipn'));
            $billplz = PaymentGatewayCredential::get_billplz_credential();
            return $billplz->charge_customer($params);
        }
        elseif($selected_payment_gateway == 'zitopay')
        {
            $params = $this->common_charge_customer_data($amount_to_charge,$payment_details,$request,route('tenant.frontend.event.zitopay.ipn'));
            $zitopay = PaymentGatewayCredential::get_zitopay_credential();
            return $zitopay->charge_customer($params);
        }
        elseif($selected_payment_gateway == 'toyyibpay')
        {
            $params = $this->common_charge_customer_data($amount_to_charge,$payment_details,$request,route('tenant.frontend.event.toyyibpay.ipn'));
            $toyyibpay = PaymentGatewayCredential::get_toyyibpay_credential();
            return $toyyibpay->charge_customer($params);
        }
        elseif($selected_payment_gateway == 'pagali')
        {
            $params = $this->common_charge_customer_data($amount_to_charge,$payment_details,$request,route('tenant.frontend.event.pagali.ipn'));
            $pagali = PaymentGatewayCredential::get_pagali_credential();
            return $pagali->charge_customer($params);
        }
        elseif($selected_payment_gateway == 'authorizenet')
        {
            $params = $this->common_charge_customer_data($amount_to_charge,$payment_details,$request,route('tenant.frontend.event.authorizenet.ipn'));
            $authorizenet = PaymentGatewayCredential::get_authorizenet_credential();
            return $authorizenet->charge_customer($params);
        }
        elseif($selected_payment_gateway == 'sitesway')
        {
            $params = $this->common_charge_customer_data($amount_to_charge,$payment_details,$request,route('tenant.frontend.event.sitesway.ipn'));
            $sitesway = PaymentGatewayCredential::get_sitesway_credential();

            return $sitesway->charge_customer($params);
        }
        elseif($selected_payment_gateway == 'kinetic')
        {
            $params = $this->common_charge_customer_data($amount_to_charge,$payment_details,$request,route('tenant.frontend.event.kinetic.ipn'));
            $kinetic = PaymentGatewayCredential::get_kinetic_credential();

            return $kinetic->charge_customer($params);
        }

        elseif ($selected_payment_gateway == 'bank_transfer')
        {

            $this->validate($request, [
                'manual_payment_attachment' => 'required'
            ], ['manual_payment_attachment.required' => __('Bank Attachment Required')]);

            $fileName = time().'.'.$request->manual_payment_attachment->extension();
            $request->manual_payment_attachment->move('assets/uploads/attachment/',$fileName);

            EventPaymentLog::where('id', $payment_details->id)->update([
                'status' => 0,
                'manual_payment_attachment' => $fileName
            ]);

            $customer_subject = __('Your Event booking payment sent and it is in admin approval stage..!').' '.get_static_option('site_'.get_user_lang().'_title');
            $admin_subject = __('You have a new event booking with bank transfer, please check and approve..!').' '.get_static_option('site_'.get_user_lang().'_title');

            try {
                Mail::to(get_static_option('tenant_site_global_email'))->send(new EventMail($payment_details, $admin_subject,"admin"));
                Mail::to($payment_details->email)->send(new EventMail( $payment_details, $customer_subject,'user'));

            } catch (\Exception $e) {
                return redirect()->back()->with(['type' => 'danger', 'msg' => $e->getMessage()]);
            }

            $order_id = Str::random(6) .$payment_details->id . Str::random(6);
            return redirect()->route(self::SUCCESS_ROUTE,$order_id);

        }else if($selected_payment_gateway == 'manual_payment_') {

            $this->validate($request, [
                'transaction_id' => 'required'
            ]);

            $customer_subject = __('Your event payment sent and it is in admin approval stage..!').' '.get_static_option('site_'.get_user_lang().'_title');
            $admin_subject = __('You have a new event with manual payment, please check and approve..!').' '.get_static_option('site_'.get_user_lang().'_title');

            try {
                Mail::to(get_static_option('tenant_site_global_email'))->send(new EventMail($payment_details, $admin_subject,"admin"));
                Mail::to($payment_details->email)->send(new EventMail( $payment_details, $customer_subject,'user'));

            } catch (\Exception $e) {
                return redirect()->back()->with(['type' => 'danger', 'msg' => $e->getMessage()]);
            }

            EventPaymentLog::where('id', $payment_details->id)->update([
                'transaction_id' => $request->transaction_id,
                'status' => 0,
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
            $custom_data['total'] = $payment_details->amount;

            //add extra param support to the shop checkout payment system
            $custom_data['payment_type'] = "event";
            $custom_data['payment_for'] = "tenant";
            $custom_data['cancel_url'] = route(self::CANCEL_ROUTE);
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
                    route('tenant.frontend.event.'.$selected_payment_gateway.'.ipn')
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
        $payment_data = $paypal->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function paytm_ipn()
    {
        $paytm = PaymentGatewayCredential::get_paytm_credential();
        $payment_data = $paytm->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function flutterwave_ipn()
    {
        $flutterwave = PaymentGatewayCredential::get_flutterwave_credential();
        $payment_data = $flutterwave->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function stripe_ipn()
    {
        $stripe = PaymentGatewayCredential::get_stripe_credential();
        $payment_data = $stripe->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function razorpay_ipn()
    {
        $razorpay = PaymentGatewayCredential::get_razorpay_credential();
        $payment_data = $razorpay->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function paystack_ipn()
    {
        $paystack = PaymentGatewayCredential::get_paystack_credential();
        $payment_data = $paystack->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function payfast_ipn()
    {
        $payfast = PaymentGatewayCredential::get_payfast_credential();
        $payment_data = $payfast->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function mollie_ipn()
    {
        $mollie = PaymentGatewayCredential::get_mollie_credential();
        $payment_data = $mollie->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function midtrans_ipn()
    {
        $midtrans = PaymentGatewayCredential::get_midtrans_credential();
        $payment_data = $midtrans->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function cashfree_ipn()
    {
        $cashfree = PaymentGatewayCredential::get_cashfree_credential();
        $payment_data = $cashfree->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function instamojo_ipn()
    {
        $instamojo = PaymentGatewayCredential::get_instamojo_credential();
        $payment_data = $instamojo->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function marcadopago_ipn()
    {
        $marcadopago = PaymentGatewayCredential::get_marcadopago_credential();
        $payment_data = $marcadopago->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function squareup_ipn()
    {
        $squareup = PaymentGatewayCredential::get_squareup_credential();
        $payment_data = $squareup->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function cinetpay_ipn()
    {
        $cinetpay = PaymentGatewayCredential::get_cinetpay_credential();
        $payment_data = $cinetpay->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function paytabs_ipn()
    {
        $paytabs = PaymentGatewayCredential::get_paytabs_credential();
        $payment_data = $paytabs->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function billplz_ipn()
    {
        $billplz = PaymentGatewayCredential::get_billplz_credential();
        $payment_data = $billplz->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function zitopay_ipn()
    {
        $zitopay = PaymentGatewayCredential::get_zitopay_credential();
        $payment_data = $zitopay->ipn_response();
        return $this->common_ipn_data($payment_data);
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

    public function kinetic_ipn()
    {
        $kinetic_ipn = PaymentGatewayCredential::get_kinetic_credential();
        $payment_data = $kinetic_ipn->ipn_response();
        return $this->common_ipn_data($payment_data);
    }


    private function common_charge_customer_data($amount_to_charge,$payment_details,$request,$ipn_url) : array
    {
        $data = [
            'amount' => $amount_to_charge,
            'title' => $payment_details->event?->getTranslation('title',get_user_lang()),
            'description' => 'Payment For event Id: #' . $payment_details->package_id . ' Donation Name: ' . $payment_details->event?->getTranslation('title',get_user_lang()),
            'Payer Name: ' . $request->name . ' Payer Email:' . $request->email,
            'order_id' => $payment_details->id,
            'track' => $payment_details->track,
            'cancel_url' => route(self::CANCEL_ROUTE, $payment_details->id),
            'success_url' => route(self::SUCCESS_ROUTE, $payment_details->id),
            'email' => $payment_details->email,
            'name' => $payment_details->name,
            'payment_type' => 'order',
            'ipn_url' => $ipn_url,
        ];

        return $data;
    }

    private function common_ipn_data($payment_data)
    {
        if (isset($payment_data['status']) && $payment_data['status'] === 'complete') {
            TenantEvent::update_database($payment_data['order_id'], $payment_data['transaction_id'] ?? Str::random(15));
            TenantEvent::send_event_mail($payment_data['order_id']);
           $order_id = wrap_random_number($payment_data['order_id']);

           return redirect()->route(self::SUCCESS_ROUTE, $order_id);
        }
        return redirect()->route(self::CANCEL_ROUTE);
    }

}
