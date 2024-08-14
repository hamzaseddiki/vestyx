<?php

namespace App\Http\Controllers\Tenant\Frontend;

use App\Http\Controllers\Controller;
use App\Mail\PlaceOrderTenant;
use App\Models\FormBuilder;
use App\Models\PaymentGateway;
use App\Models\PaymentLogs;
use App\Models\PricePlan;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Xgenious\Paymentgateway\Facades\XgPaymentGateway;


class PaymentLogController extends Controller
{

    protected function cancel_page(){
        return redirect()->route('tenant.frontend.order.payment.cancel.static');
    }

    public function order_payment_form(Request $request)
    {

        $manual_transection_condition = $request->selected_payment_gateway == 'manual_payment_' ? 'required' : 'nullable';

        $this->validate($request, [
            'name' => 'required|string|max:191',
            'email' => 'required|email|max:191',
            'package_id' => 'required|string',
            'payment_gateway' => 'nullable|string',
            'trasaction_id' => ''.$manual_transection_condition.'',
        ]);

        $order_details = PricePlan::find($request->package);
        $amount_to_charge =  $order_details->price;

        $custom_form_data = FormBuilder::find($request->custom_form_id);
        $request_date_remove = $request;
        $selected_payment_gateway = $request_date_remove['selected_payment_gateway'];
        $package_id = $request_date_remove['package_id'];
        $name = $request_date_remove['name'];
        $email = $request_date_remove['email'];
        $trasaction_id = $request_date_remove['trasaction_id'];


        unset($request_date_remove['custom_form_id']);
        unset($request_date_remove['selected_payment_gateway']);
        unset($request_date_remove['payment_gateway']);
        unset($request_date_remove['package_id']);
        unset($request_date_remove['package']);
        unset($request_date_remove['pkg_user_name']);
        unset($request_date_remove['pkg_user_email']);
        unset($request_date_remove['name']);
        unset($request_date_remove['email']);
        unset($request_date_remove['trasaction_id']);

        $validated_data = $this->get_filtered_data_from_request($custom_form_data->fields,$request_date_remove,false) ?? [];
        $all_attachment = $validated_data['all_attachment'] ?? [];
        $all_field_serialize_data = $validated_data['field_data'] ?? [];


        $payment_log_id = PaymentLogs::create([
            'custom_fields' => json_encode($all_field_serialize_data) ?? [],
            'attachments' => json_encode($all_attachment) ?? [],
            'email' => $email,
            'name' => $name,
            'package_name' => $order_details->getTranslation('title',get_user_lang()),
            'package_price' => $amount_to_charge,
            'package_gateway' => $selected_payment_gateway,
            'package_id' => $package_id,
            'user_id' => auth()->guard('web')->user()->id ?? null,
            'status' => 'pending',
            'track' => Str::random(10) . Str::random(10),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),

        ])->id;

        $payment_details = PaymentLogs::find($payment_log_id);

        if ($selected_payment_gateway === 'paypal') {

            $paypal_credential_from_database = PaymentGateway::where('name','paypal')->first();
            $decoded = json_decode($paypal_credential_from_database->credentials);

            $sandbox_client_id =$decoded->sandbox_client_id ?? '';
            $sandbox_client_secret =$decoded->sandbox_client_secret ?? '';
            $sandbox_app_id =$decoded->sandbox_app_id ?? '';

            $live_client_id = $decoded->live_client_id ?? '';
            $live_client_secret = $decoded->live_client_secret ?? '';
            $live_app_id = $decoded->live_app_id ?? '';

            $checked_client_id = empty($live_client_id) ? $sandbox_client_id : $live_client_id;
            $checked_client_secret = empty($live_client_secret) ? $sandbox_client_secret : $live_client_secret;
            $checked_app_id = empty($live_app_id) ? $sandbox_app_id : $live_app_id;

            $sandbox_and_live_check_mode = $paypal_credential_from_database->test_mode == 1 ? 'true' : 'false';

            $global_currency = get_static_option('site_global_currency');
            $usd_exchange_rate = get_static_option('site_' . strtolower($global_currency) . '_to_usd_exchange_rate');
            $checked_currency_rate = empty($usd_exchange_rate) ?  74 : $usd_exchange_rate;

            try {

                $paypal = XgPaymentGateway::paypal();
                $paypal->setClientId($checked_client_id); // provide sandbox id if payment env set to true, otherwise provide live credentials
                $paypal->setClientSecret($checked_client_secret); // provide sandbox id if payment env set to true, otherwise provide live credentials
                $paypal->setAppId($checked_app_id); // provide sandbox id if payment env set to true, otherwise provide live credentials
                $paypal->setCurrency($global_currency);
                $paypal->setEnv($sandbox_and_live_check_mode);
                $paypal->setExchangeRate($checked_currency_rate); // if INR not set as currency

                $response =  $paypal->charge_customer([
                    'amount' => $amount_to_charge,
                    'title' => $payment_details->package_name,
                    'description' => 'Payment For Package Order Id: #' . $package_id . ' Package Name: ' . $payment_details->package_name . ' Payer Name: ' . $name . ' Payer Email:' . $email,
                    'ipn_url' => route('tenant.frontend.paypal.ipn'), //get route
                    'order_id' => $order_details->id,
                    'track' => $payment_details->track,
                    'cancel_url' => route('tenant.frontend.order.payment.cancel',$payment_details->id),
                    'success_url' => route('tenant.frontend.order.payment.success', $payment_details->id),
                    'email' => $payment_details->email,
                    'name' =>  $payment_details->name,
                    'payment_type' => 'order',
                ]);

                return $response;


            }catch (\Exception $e){
                return redirect()->back()->with(['msg'=> $e->getMessage(),'type'=>'danger']);
            }

        } elseif ($selected_payment_gateway === 'paytm') {

            $paypal_credential_from_database = PaymentGateway::where('name','paytm')->first();
            $decoded = json_decode($paypal_credential_from_database->credentials);
            $merchant_id =$decoded->merchant_mid ?? '';
            $merchant_key =$decoded->merchant_key ?? '';
            $merchant_website =$decoded->merchant_website ?? '';
            $channel =$decoded->channel ?? '';
            $industry_type =$decoded->industry_type ?? '';

            $global_currency = get_static_option('site_global_currency');
            $inr_exchange_rate = get_static_option('site_' . strtolower($global_currency) . '_to_inr_exchange_rate');
            $checked_currency_rate = empty($inr_exchange_rate) ?  74 : $inr_exchange_rate;

            $paytm = XgPaymentGateway::paytm();
            $paytm->setMerchantId($merchant_id);
            $paytm->setMerchantKey($merchant_key);
            $paytm->setMerchantWebsite($merchant_website);
            $paytm->setChannel($channel);
            $paytm->setIndustryType($industry_type);
            $paytm->setCurrency($global_currency);
            $paytm->setEnv('true');
            $paytm->setExchangeRate($checked_currency_rate);

            try {
                $redirect_url = $paytm->charge_customer([
                    'amount' => $amount_to_charge,
                    'title' => $payment_details->package_name,
                    'description' => 'Payment For Package Order Id: #' . $package_id . ' Package Name: ' . $payment_details->package_name . ' Payer Name: ' . $name . ' Payer Email:' . $email,
                    'order_id' => $payment_details->id,
                    'track' => $payment_details->track,
                    'cancel_url' => route('tenant.frontend.order.payment.cancel',$payment_details->id),
                    'success_url' => route('tenant.frontend.order.payment.success', $payment_details->id),
                    'email' => $payment_details->email, // user email
                    'name' => $payment_details->name, // user name
                    'payment_type' => 'order', // which kind of payment your are receiving
                    'ipn_url' => route('tenant.frontend.paytm.ipn')
                ]);


                return $redirect_url;

            }catch (\Exception $e){
                return redirect()->back()->with(['msg'=> $e->getMessage(),'type'=>'danger']);
            }

        } elseif ($selected_payment_gateway === 'mollie') {

            $paypal_credential_from_database = PaymentGateway::where('name','mollie')->first();
            $decoded = json_decode($paypal_credential_from_database->credentials);
            $public_key = $decoded->public_key ?? '';

            $global_currency = get_static_option('site_global_currency');
            $inr_exchange_rate = get_static_option('site_' . strtolower($global_currency) . '_to_inr_exchange_rate');
            $checked_currency_rate = empty($inr_exchange_rate) ?  74 : $inr_exchange_rate;

            $mollie = XgPaymentGateway::mollie();
            $mollie->setApiKey($public_key);
            $mollie->setCurrency($global_currency);
            $mollie->setEnv(true); //env must set as boolean, string will not work
            $mollie->setExchangeRate($checked_currency_rate); // if INR not set as currency

            try {
                $redirect_url = $mollie->charge_customer([
                    'amount' => $amount_to_charge,
                    'title' => $payment_details->package_name,
                    'description' => 'Payment For Package Order Id: #' . $package_id . ' Package Name: ' . $payment_details->package_name . ' Payer Name: ' . $name . ' Payer Email:' . $email,
                    'order_id' => $payment_details->id,
                    'track' => $payment_details->track,
                    'cancel_url' => route('tenant.frontend.order.payment.cancel',$payment_details->id),
                    'success_url' => route('tenant.frontend.order.payment.success', $payment_details->id),
                    'email' => $payment_details->email, // user email
                    'name' => $payment_details->name, // user name
                    'payment_type' => 'order', // which kind of payment your are receiving
                    'ipn_url' => route('tenant.frontend.mollie.ipn')
                ]);
                return $redirect_url;

            }catch (\Exception $e){
                return redirect()->back()->with(['msg'=> $e->getMessage(),'type'=>'danger']);
            }

        } elseif ($selected_payment_gateway === 'stripe') {

            $paypal_credential_from_database = PaymentGateway::where('name','stripe')->first();
            $decoded = json_decode($paypal_credential_from_database->credentials);

            $public_key =$decoded->public_key ?? '';
            $secret_key =$decoded->secret_key ?? '';

            $global_currency = get_static_option('site_global_currency');
            $inr_exchange_rate = get_static_option('site_' . strtolower($global_currency) . '_to_inr_exchange_rate');
            $checked_currency_rate = empty($inr_exchange_rate) ?  74 : $inr_exchange_rate;

            $stripe = XgPaymentGateway::stripe();
            $stripe->setPublicKey($public_key);
            $stripe->setSecretKey($secret_key);
            $stripe->setCurrency($global_currency);
            $stripe->setEnv('true');
            $stripe->setExchangeRate($checked_currency_rate); // if INR not set as currency

            try {
                $redirect_url = $stripe->charge_customer([
                    'amount' => $amount_to_charge,
                    'title' => $payment_details->package_name,
                    'description' => 'Payment For Package Order Id: #' . $package_id . ' Package Name: ' . $payment_details->package_name . ' Payer Name: ' . $name . ' Payer Email:' . $email,
                    'order_id' => $payment_details->id,
                    'track' => $payment_details->track,
                    'cancel_url' => route('tenant.frontend.order.payment.cancel',$payment_details->id),
                    'success_url' => route('tenant.frontend.order.payment.success', $payment_details->id),
                    'email' => $payment_details->email, // user email
                    'name' => $payment_details->name, // user name
                    'payment_type' => 'order', // which kind of payment your are receiving
                    'ipn_url' => route('tenant.frontend.stripe.ipn')
                ]);

                return $redirect_url;


            }catch (\Exception $e){
                return redirect()->back()->with(['msg'=> $e->getMessage(),'type'=>'danger']);
            }

        } elseif ($selected_payment_gateway === 'razorpay') {

            $paypal_credential_from_database = PaymentGateway::where('name','razorpay')->first();
            $decoded = json_decode($paypal_credential_from_database->credentials);

            $api_key =$decoded->api_key ?? '';
            $api_secret =$decoded->api_secret ?? '';

            $global_currency = get_static_option('site_global_currency');
            $inr_exchange_rate = get_static_option('site_' . strtolower($global_currency) . '_to_inr_exchange_rate');
            $checked_currency_rate = empty($inr_exchange_rate) ?  74 : $inr_exchange_rate;

            $razorpay = XgPaymentGateway::razorpay();
            $razorpay->setApiKey($api_key);
            $razorpay->setApiSecret($api_secret);
            $razorpay->setCurrency($global_currency);
            $razorpay->setEnv('true');
            $razorpay->setExchangeRate($checked_currency_rate); // if INR not set as currency

            try {
                $redirect_url = $razorpay->charge_customer([
                    'amount' => $amount_to_charge,
                    'title' => $payment_details->package_name,
                    'description' => 'Payment For Package Order Id: #' . $package_id . ' Package Name: ' . $payment_details->package_name . ' Payer Name: ' . $name . ' Payer Email:' . $email,
                    'order_id' => $payment_details->id,
                    'track' => $payment_details->track,
                    'cancel_url' => route('tenant.frontend.order.payment.cancel',$payment_details->id),
                    'success_url' => route('tenant.frontend.order.payment.success', $payment_details->id),
                    'email' => $payment_details->email, // user email
                    'name' => $payment_details->name, // user name
                    'payment_type' => 'order', // which kind of payment your are receiving
                    'ipn_url' => route('tenant.frontend.razorpay.ipn')
                ]);

                return $redirect_url;

            }catch (\Exception $e){
                return redirect()->back()->with(['msg'=> $e->getMessage(),'type'=>'danger']);
            }

        } elseif ($selected_payment_gateway === 'flutterwave') {

            $paypal_credential_from_database = PaymentGateway::where('name','flutterwave')->first();
            $decoded = json_decode($paypal_credential_from_database->credentials);


            $public_key =$decoded->public_key ?? '';
            $secret_key =$decoded->secret_key ?? '';

            $global_currency = get_static_option('site_global_currency');
            $inr_exchange_rate = get_static_option('site_' . strtolower($global_currency) . '_to_ngn_exchange_rate');
            $checked_currency_rate = empty($inr_exchange_rate) ?  74 : $inr_exchange_rate;


            $flutterwave = XgPaymentGateway::flutterwave();
            $flutterwave->setPublicKey($public_key);
            $flutterwave->setSecretKey($secret_key);
            $flutterwave->setCurrency($global_currency);
            $flutterwave->setEnv(true); //env must set as boolean, string will not work
            $flutterwave->setExchangeRate($checked_currency_rate); // if NGN not set as currency

            try {
                $redirect_url = $flutterwave->charge_customer([
                    'amount' => $amount_to_charge,
                    'title' => $payment_details->package_name,
                    'description' => 'Payment For Package Order Id: #' . $package_id . ' Package Name: ' . $payment_details->package_name . ' Payer Name: ' . $name . ' Payer Email:' . $email,
                    'order_id' => $payment_details->id,
                    'track' => $payment_details->track,
                    'cancel_url' => route('tenant.frontend.order.payment.cancel',$payment_details->id),
                    'success_url' => route('tenant.frontend.order.payment.success', $payment_details->id),
                    'email' => $payment_details->email, // user email
                    'name' => $payment_details->name, // user name
                    'payment_type' => 'order', // which kind of payment your are receiving
                    'ipn_url' => route('tenant.frontend.flutterwave.ipn')
                ]);

                return $redirect_url;

            }catch (\Exception $e){
                return redirect()->back()->with(['msg'=> $e->getMessage(),'type'=>'danger']);
            }

        } elseif ($selected_payment_gateway === 'paystack') {

            $paypal_credential_from_database = PaymentGateway::where('name','paystack')->first();
            $decoded = json_decode($paypal_credential_from_database->credentials);

            $public_key =$decoded->public_key ?? '';
            $secret_key =$decoded->secret_key ?? '';
            $marchant_email =$decoded->marchant_email ?? '';

            $global_currency = get_static_option('site_global_currency');
            $ngn_exchange_rate = get_static_option('site_' . strtolower($global_currency) . '_to_ngn_exchange_rate');
            $checked_currency_rate = empty($ngn_exchange_rate) ?  74 : $ngn_exchange_rate;

            $paystack = XgPaymentGateway::paystack();
            $paystack->setPublicKey($public_key);
            $paystack->setSecretKey($secret_key);
            $paystack->setMerchantEmail($marchant_email);
            $paystack->setCurrency($global_currency);
            $paystack->setEnv('true');
            $paystack->setExchangeRate($checked_currency_rate); // if NGN not set as currenc

            try {
                $redirect_url = $paystack->charge_customer([
                    'amount' => $amount_to_charge,
                    'title' => $payment_details->package_name,
                    'description' => 'Payment For Package Order Id: #' . $package_id . ' Package Name: ' . $payment_details->package_name . ' Payer Name: ' . $name . ' Payer Email:' . $email,
                    'order_id' => $payment_details->id,
                    'track' => $payment_details->track,
                    'cancel_url' => route('tenant.frontend.order.payment.cancel',$payment_details->id),
                    'success_url' => route('tenant.frontend.order.payment.success', $payment_details->id),
                    'email' => $payment_details->email, // user email
                    'name' => $payment_details->name, // user name
                    'payment_type' => 'order', // which kind of payment your are receiving
                    'ipn_url' => route('tenant.frontend.paystack.ipn')
                ]);

                return $redirect_url;

            }catch (\Exception $e){
                return redirect()->back()->with(['msg'=> $e->getMessage(),'type'=>'danger']);
            }

        } elseif ($selected_payment_gateway === 'midtrans') {

            $paypal_credential_from_database = PaymentGateway::where('name','midtrans')->first();
            $decoded = json_decode($paypal_credential_from_database->credentials);

            $server_key = $decoded->server_key ?? '';
            $client_key = $decoded->server_key ?? '';

            $global_currency = get_static_option('site_global_currency') ?? "IDR";
            $idr_exchange_rate = get_static_option('site_'.strtolower($global_currency).'_to_idr_exchange_rate');
            $checked_currency_rate = empty($idr_exchange_rate) ?  74 : $idr_exchange_rate;

            $midtrans = XgPaymentGateway::midtrans();
            $midtrans->setClientKey($client_key);
            $midtrans->setServerKey($server_key);
            $midtrans->setCurrency($global_currency);
            $midtrans->setEnv('true'); //true mean sandbox mode , false means live mode
            $midtrans->setExchangeRate($checked_currency_rate); // if IDR not set as currency

            try {
                $redirect_url = $midtrans->charge_customer([
                    'amount' => $amount_to_charge,
                    'title' => $payment_details->package_name,
                    'description' => 'Payment For Package Order Id: #' . $package_id . ' Package Name: ' . $payment_details->package_name . ' Payer Name: ' . $name . ' Payer Email:' . $email,
                    'order_id' => $payment_details->id,
                    'track' => $payment_details->track,
                    'cancel_url' => route('tenant.frontend.order.payment.cancel',$payment_details->id),
                    'success_url' => route('tenant.frontend.order.payment.success', $payment_details->id),
                    'email' => $payment_details->email, // user email
                    'name' => $payment_details->name, // user name
                    'payment_type' => 'order', // which kind of payment your are receiving
                    'ipn_url' => route('tenant.frontend.midtrans.ipn')
                ]);

                return $redirect_url;

            }catch (\Exception $e){
                return redirect()->back()->with(['msg'=> $e->getMessage(),'type'=>'danger']);
            }

        } elseif ($selected_payment_gateway == 'payfast') {

            $paypal_credential_from_database = PaymentGateway::where('name','payfast')->first();
            $decoded = json_decode($paypal_credential_from_database->credentials);

            $merchant_id = $decoded->merchant_id ?? '';
            $merchant_key = $decoded->merchant_key ?? '';
            $passphrase = $decoded->passphrase ?? '';

            $global_currency = get_static_option('site_global_currency') ?? "IDR";
            $inr_exchange_rate = get_static_option('site_'.strtolower($global_currency).'_to_inr_exchange_rate');
            $checked_currency_rate = empty($inr_exchange_rate) ?  74 : $inr_exchange_rate;

            $payfast = XgPaymentGateway::payfast();
            $payfast->setMerchantId($merchant_id);
            $payfast->setMerchantKey($merchant_key);
            $payfast->setPassphrase($passphrase);
            $payfast->setCurrency($global_currency);
            $payfast->setEnv(true); //env must set as boolean, string will not work
            $payfast->setExchangeRate($checked_currency_rate); // if INR not set
            $payfast_custom_redirect_id = Str::random(6) . $payment_details->id. Str::random(6);


            try {
                $redirect_url = $payfast->charge_customer([
                    'amount' => $amount_to_charge,
                    'title' => $payment_details->package_name,
                    'description' => 'Payment For Package Order Id: #' . $package_id . ' Package Name: ' . $payment_details->package_name . ' Payer Name: ' . $name . ' Payer Email:' . $email,
                    'order_id' => $payment_details->id,
                    'track' => $payment_details->track,
                    'cancel_url' => route('tenant.frontend.order.payment.cancel',$payment_details->id),
                    'success_url' => route('tenant.frontend.order.payment.success', $payfast_custom_redirect_id),
                    'email' => $payment_details->email, // user email
                    'name' => $payment_details->name, // user name
                    'payment_type' => 'order', // which kind of payment your are receiving
                    'ipn_url' => route('tenant.frontend.payfast.ipn')
                ]);

                $this->update_database($payment_details->id,Str::random(16));

                return $redirect_url;

            }catch (\Exception $e){
                return redirect()->back()->with(['msg'=> $e->getMessage(),'type'=>'danger']);
            }

        } elseif ($selected_payment_gateway == 'cashfree') {

            $paypal_credential_from_database = PaymentGateway::where('name','cashfree')->first();
            $decoded = json_decode($paypal_credential_from_database->credentials);

            $app_id = $decoded->app_id ?? '';
            $secret_key = $decoded->secret_key ?? '';

            $global_currency = get_static_option('site_global_currency') ;
            $inr_exchange_rate = get_static_option('site_'.strtolower($global_currency).'_to_inr_exchange_rate');
            $checked_currency_rate = empty($inr_exchange_rate) ?  74 : $inr_exchange_rate;

            $cashfree = XgPaymentGateway::cashfree();
            $cashfree->setAppId($app_id);
            $cashfree->setSecretKey($secret_key);
            $cashfree->setCurrency($global_currency);
            $cashfree->setEnv('true'); //true means sandbox, false means live
            $cashfree->setExchangeRate($checked_currency_rate); // if INR not set as currency

            try {
                $redirect_url = $cashfree->charge_customer([
                    'amount' => $amount_to_charge,
                    'title' => $payment_details->package_name,
                    'description' => 'Payment For Package Order Id: #' . $package_id . ' Package Name: ' . $payment_details->package_name . ' Payer Name: ' . $name . ' Payer Email:' . $email,
                    'order_id' => $payment_details->id,
                    'track' => $payment_details->track,
                    'cancel_url' => route('tenant.frontend.order.payment.cancel',$payment_details->id),
                    'success_url' => route('tenant.frontend.order.payment.success', $payment_details->id),
                    'email' => $payment_details->email, // user email
                    'name' => $payment_details->name, // user name
                    'payment_type' => 'order', // which kind of payment your are receiving
                    'ipn_url' => route('tenant.frontend.cashfree.ipn')
                ]);

                return $redirect_url;

            }catch (\Exception $e){
                return redirect()->back()->with(['msg'=> $e->getMessage(),'type'=>'danger']);
            }

        } elseif ($selected_payment_gateway == 'instamojo') {

            $paypal_credential_from_database = PaymentGateway::where('name','instamojo')->first();
            $decoded = json_decode($paypal_credential_from_database->credentials);

            $client_id = $decoded->client_id ?? '';
            $client_secret = $decoded->client_secret ?? '';

            $global_currency = get_static_option('site_global_currency') ;
            $inr_exchange_rate = get_static_option('site_'.strtolower($global_currency).'_to_inr_exchange_rate');
            $checked_currency_rate = empty($inr_exchange_rate) ?  74 : $inr_exchange_rate;

            $instamojo = XgPaymentGateway::instamojo();
            $instamojo->setClientId($client_id);
            $instamojo->setSecretKey($client_secret);
            $instamojo->setCurrency($global_currency);
            $instamojo->setEnv('true'); //true mean sandbox mode , false means live mode
            $instamojo->setExchangeRate($checked_currency_rate); // if INR not set as currency

            try {
                $redirect_url = $instamojo->charge_customer([
                    'amount' => $amount_to_charge,
                    'title' => $payment_details->package_name,
                    'description' => 'Payment For Package Order Id: #' . $package_id . ' Package Name: ' . $payment_details->package_name . ' Payer Name: ' . $name . ' Payer Email:' . $email,
                    'order_id' => $payment_details->id,
                    'track' => $payment_details->track,
                    'cancel_url' => route('tenant.frontend.order.payment.cancel',$payment_details->id),
                    'success_url' => route('tenant.frontend.order.payment.success', $payment_details->id),
                    'email' => $payment_details->email, // user email
                    'name' => $payment_details->name, // user name
                    'payment_type' => 'order', // which kind of payment your are receiving
                    'ipn_url' => route('tenant.frontend.instamojo.ipn')
                ]);

                return $redirect_url;

            }catch (\Exception $e){
                return redirect()->back()->with(['msg'=> $e->getMessage(),'type'=>'danger']);
            }

        } elseif ($selected_payment_gateway == 'marcadopago') {

            $paypal_credential_from_database = PaymentGateway::where('name','marcadopago')->first();
            $decoded = json_decode($paypal_credential_from_database->credentials);

            $client_id = $decoded->client_id ?? '';
            $client_secret = $decoded->client_secret ?? '';

            $global_currency = get_static_option('site_global_currency') ;
            $brl_exchange_rate = get_static_option('site_'.strtolower($global_currency).'_to_brl_exchange_rate');
            $checked_currency_rate = empty($brl_exchange_rate) ?  74 : $brl_exchange_rate;

            $marcadopago = XgPaymentGateway::marcadopago();
            $marcadopago->setClientId($client_id);
            $marcadopago->setClientSecret($client_secret);
            $marcadopago->setCurrency($global_currency);
            $marcadopago->setExchangeRate($checked_currency_rate); // if BRL not set as currency, you must have to provide exchange rate for it
            $marcadopago->setEnv(true); ////true mean sandbox mode , false means live mode

            try {
                $redirect_url = $marcadopago->charge_customer([
                    'amount' => $amount_to_charge,
                    'title' => $payment_details->package_name,
                    'description' => 'Payment For Package Order Id: #' . $package_id . ' Package Name: ' . $payment_details->package_name . ' Payer Name: ' .$name . ' Payer Email:' . $email,
                    'order_id' => $payment_details->id,
                    'track' => $payment_details->track,
                    'cancel_url' => route('tenant.frontend.order.payment.cancel',$payment_details->id),
                    'success_url' => route('tenant.frontend.order.payment.success', $payment_details->id),
                    'email' => $payment_details->email, // user email
                    'name' => $payment_details->name, // user name
                    'payment_type' => 'order', // which kind of payment your are receiving
                    'ipn_url' => route('tenant.frontend.instamojo.ipn')
                ]);

                return $redirect_url;

            }catch (\Exception $e){
                return redirect()->back()->with(['msg'=> $e->getMessage(),'type'=>'danger']);
            }

        } elseif ($selected_payment_gateway == 'manual_payment_') {
            PaymentLogs::where('package_id', $package_id)->update(['transaction_id' => $trasaction_id]);
            $this->send_order_mail($order_details->id);
            $order_id = Str::random(6) . $package_id . Str::random(6);
            return redirect()->route('tenant.frontend.order.payment.success', $order_id);

        }
        return redirect()->route('homepage');
    }

    public function paypal_ipn(Request $request)
    {

        $paypal_credential_from_database = PaymentGateway::where('name','paypal')->first();
        $decoded = json_decode($paypal_credential_from_database->credentials);

        $sandbox_client_id =$decoded->sandbox_client_id ?? '';
        $sandbox_client_secret =$decoded->sandbox_client_secret ?? '';
        $sandbox_app_id =$decoded->sandbox_app_id ?? '';

        $live_client_id = $decoded->live_client_id ?? '';
        $live_client_secret = $decoded->live_client_secret ?? '';
        $live_app_id = $decoded->live_app_id ?? '';

        $checked_client_id = empty($live_client_id) ? $sandbox_client_id : $live_client_id;
        $checked_client_secret = empty($live_client_secret) ? $sandbox_client_secret : $live_client_secret;
        $checked_app_id = empty($live_app_id) ? $sandbox_app_id : $live_app_id;

        $sandbox_and_live_check_mode = !empty($sandbox_client_id) && !empty($sandbox_client_secret) && !empty($sandbox_app_id) ? 'true' : 'false';

        $paypal = XgPaymentGateway::paypal();
        $paypal->setClientId($checked_client_id);
        $paypal->setClientSecret($checked_client_secret);
        $paypal->setEnv($sandbox_and_live_check_mode);
        $paypal->setAppId($checked_app_id);

        $payment_data = XgPaymentGateway::paypal()->ipn_response();
        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $this->update_database($payment_data['order_id'], $payment_data['transaction_id']);
            $this->send_order_mail($payment_data['order_id']);
            $order_id = Str::random(6) . $payment_data['order_id'] . Str::random(6);
            return redirect()->route('tenant.frontend.order.payment.success',$order_id);
        }
          return $this->cancel_page();
    }

    public function razorpay_ipn(Request $request)
    {

        $paypal_credential_from_database = PaymentGateway::where('name','razorpay')->first();
        $decoded = json_decode($paypal_credential_from_database->credentials);

        $api_key =$decoded->api_key ?? '';
        $api_secret =$decoded->api_secret ?? '';

        $razorpay = XgPaymentGateway::razorpay();
        $razorpay->setApiKey($api_key);
        $razorpay->setApiSecret($api_secret);
        $razorpay->setEnv('true');

        $payment_data = $razorpay->ipn_response();

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $this->update_database($payment_data['order_id'], $payment_data['transaction_id']);
            $this->send_order_mail($payment_data['order_id']);
            $order_id = Str::random(6) . $payment_data['order_id'] . Str::random(6);
            return redirect()->route('tenant.frontend.order.payment.success',$order_id);
        }
        return $this->cancel_page();
    }

    public function paytm_ipn(Request $request)
    {
        $paypal_credential_from_database = PaymentGateway::where('name','paytm')->first();
        $decoded = json_decode($paypal_credential_from_database->credentials);

        $merchant_id =$decoded->merchant_mid ?? '';
        $merchant_key =$decoded->merchant_key ?? '';
        $merchant_website =$decoded->merchant_website ?? '';
        $channel =$decoded->channel ?? '';
        $industry_type =$decoded->industry_type ?? '';

        $paytm = XgPaymentGateway::paytm();
        $paytm->setMerchantId($merchant_id);
        $paytm->setMerchantKey($merchant_key);
        $paytm->setMerchantWebsite($merchant_website);
        $paytm->setChannel($channel);
        $paytm->setIndustryType($industry_type);
        $paytm->setEnv('true');

        $payment_data = $paytm->ipn_response();

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $this->update_database($payment_data['order_id'], $payment_data['transaction_id']);
            $this->send_order_mail($payment_data['order_id']);
            $order_id = Str::random(6) . $payment_data['order_id'] . Str::random(6);
            return redirect()->route('tenant.frontend.order.payment.success',$order_id);
        }
        return $this->cancel_page();
    }

    public function mollie_ipn()
    {
        $paypal_credential_from_database = PaymentGateway::where('name','mollie')->first();
        $decoded = json_decode($paypal_credential_from_database->credentials);
        $public_key = $decoded->public_key ?? '';

        $global_currency = get_static_option('site_global_currency');
        $inr_exchange_rate = get_static_option('site_' . strtolower($global_currency) . '_to_inr_exchange_rate');
        $checked_currency_rate = empty($inr_exchange_rate) ?  74 : $inr_exchange_rate;

        $mollie = XgPaymentGateway::mollie();
        $mollie->setApiKey($public_key);
        $mollie->setCurrency($global_currency);
        $mollie->setEnv(true); //env must set as boolean, string will not work
        $mollie->setExchangeRate($checked_currency_rate); // if INR not set as currency



        $payment_data = $mollie->ipn_response();
        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $this->update_database($payment_data['order_id'], $payment_data['transaction_id']);
            $this->send_order_mail($payment_data['order_id']);
            $order_id = Str::random(6) . $payment_data['order_id']. Str::random(6);
            return redirect()->route('tenant.frontend.order.payment.success',$order_id);
        }
        return $this->cancel_page();
    }

    public function stripe_ipn(Request $request){

        $paypal_credential_from_database = PaymentGateway::where('name','stripe')->first();
        $decoded = json_decode($paypal_credential_from_database->credentials);

        $public_key = $decoded->public_key ?? '';
        $secret_key = $decoded->secret_key ?? '';

        $global_currency = get_static_option('site_global_currency');

        $stripe = XgPaymentGateway::stripe();
        $stripe->setPublicKey($public_key);
        $stripe->setSecretKey($secret_key);
        $stripe->setCurrency($global_currency);
        $stripe->setEnv('true');

        $payment_data = $stripe->ipn_response();

        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $this->update_database($payment_data['order_id'], $payment_data['transaction_id']);
            $this->send_order_mail($payment_data['order_id']);
            $order_id = Str::random(6) . $payment_data['order_id'] . Str::random(6);
            return redirect()->route('tenant.frontend.order.payment.success',$order_id);
        }
        return $this->cancel_page();
    }


    public function flutterwave_ipn(Request $request)
    {
        $paypal_credential_from_database = PaymentGateway::where('name','flutterwave')->first();
        $decoded = json_decode($paypal_credential_from_database->credentials);


        $public_key =$decoded->public_key ?? '';
        $secret_key =$decoded->secret_key ?? '';
        $global_currency = get_static_option('site_global_currency');


        $flutterwave = XgPaymentGateway::flutterwave();
        $flutterwave->setPublicKey($public_key);
        $flutterwave->setSecretKey($secret_key);
        $flutterwave->setCurrency($global_currency);
        $flutterwave->setEnv(true);  //env must set as boolean, string will not work

        $payment_data = $flutterwave->ipn_response();
        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $this->update_database($payment_data['order_id'], $payment_data['transaction_id']);
            $this->send_order_mail($payment_data['order_id']);
            $order_id = Str::random(6) . $payment_data['order_id']. Str::random(6);
            return redirect()->route('tenant.frontend.order.payment.success',$order_id);
        }
        return $this->cancel_page();
    }


    public function paystack_ipn(Request $request)
    {
        $paypal_credential_from_database = PaymentGateway::where('name','paystack')->first();
        $decoded = json_decode($paypal_credential_from_database->credentials);

        $public_key = $decoded->public_key ?? '';
        $secret_key = $decoded->secret_key ?? '';
        $marchant_email = $decoded->marchant_email ?? '';

        $paystack = XgPaymentGateway::paystack();
        $paystack->setPublicKey($public_key);
        $paystack->setSecretKey($secret_key);
        $paystack->setMerchantEmail($marchant_email);
        $paystack->setEnv('true');

        $payment_data = $paystack->ipn_response();
        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $this->update_database($payment_data['order_id'], $payment_data['transaction_id']);
            $this->send_order_mail($payment_data['order_id']);
            $order_id = Str::random(6) . $payment_data['order_id']. Str::random(6);
            return redirect()->route(route_prefix().'frontend.order.payment.success',$order_id);
        }
        return $this->cancel_page();
    }

    public function midtrans_ipn()
    {
        $paypal_credential_from_database = PaymentGateway::where('name','midtrans')->first();
        $decoded = json_decode($paypal_credential_from_database->credentials);

        $server_key = $decoded->server_key ?? '';
        $client_key = $decoded->server_key ?? '';

        $midtrans = XgPaymentGateway::midtrans();
        $midtrans->setClientKey($client_key);
        $midtrans->setServerKey($server_key);
        $midtrans->setEnv('true'); //true mean sandbox mode , false means live

        $payment_data = $midtrans->ipn_response();
        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $this->update_database($payment_data['order_id'], $payment_data['transaction_id']);
            $this->send_order_mail($payment_data['order_id']);
            $order_id = Str::random(6) . $payment_data['order_id']. Str::random(6);
            return redirect()->route('tenant.frontend.order.payment.success',$order_id);
        }
        return $this->cancel_page();
    }

    public function payfast_ipn(Request $request)
    {


        $paypal_credential_from_database = PaymentGateway::where('name','payfast')->first();
        $decoded = json_decode($paypal_credential_from_database->credentials);

        $merchant_id = $decoded->merchant_id ?? '';
        $merchant_key = $decoded->merchant_key ?? '';
        $passphrase = $decoded->passphrase ?? '';

        $global_currency = get_static_option('site_global_currency') ?? "IDR";

        $payfast = XgPaymentGateway::payfast();
        $payfast->setMerchantId($merchant_id);
        $payfast->setMerchantKey($merchant_key);
        $payfast->setPassphrase($passphrase);
        $payfast->setCurrency($global_currency);
        $payfast->setEnv(true); //env must set as boolean, string will not work

        $payment_data = $payfast->ipn_response();
        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $this->update_database($payment_data['order_id'], $payment_data['transaction_id']);
            $this->send_order_mail($payment_data['order_id']);
            $order_id = Str::random(6) . $payment_data['order_id']. Str::random(6);

            return redirect()->route('tenant.frontend.order.payment.success',$order_id);
        }
        return $this->cancel_page();
    }

    public function cashfree_ipn(Request $request)
    {
        $paypal_credential_from_database = PaymentGateway::where('name','cashfree')->first();
        $decoded = json_decode($paypal_credential_from_database->credentials);

        $app_id = $decoded->app_id ?? '';
        $secret_key = $decoded->secret_key ?? '';

        $cashfree = XgPaymentGateway::cashfree();
        $cashfree->setAppId($app_id);
        $cashfree->setSecretKey($secret_key);
        $cashfree->setEnv('true');

        $payment_data = $cashfree->ipn_response();
        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $this->update_database($payment_data['order_id'], $payment_data['transaction_id']);
            $this->send_order_mail($payment_data['order_id']);
            $order_id = Str::random(6) . $payment_data['order_id']. Str::random(6);
            return redirect()->route('tenant.frontend.order.payment.success',$order_id);
        }
        return $this->cancel_page();

    }

    public function instamojo_ipn(Request $request)
    {
        $paypal_credential_from_database = PaymentGateway::where('name','instamojo')->first();
        $decoded = json_decode($paypal_credential_from_database->credentials);

        $client_id = $decoded->client_id ?? '';
        $client_secret = $decoded->client_secret ?? '';

        $instamojo = XgPaymentGateway::instamojo();
        $instamojo->setClientId($client_id);
        $instamojo->setSecretKey($client_secret);
        $instamojo->setEnv('true');

        $payment_data = $instamojo->ipn_response();
        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $this->update_database($payment_data['order_id'], $payment_data['transaction_id']);
            $this->send_order_mail($payment_data['order_id']);
            $order_id = Str::random(6) . $payment_data['order_id']. Str::random(6);
            return redirect()->route('tenant.frontend.order.payment.success',$order_id);
        }
        return $this->cancel_page();
    }


    public function marcadopago_ipn(Request $request)
    {

        $paypal_credential_from_database = PaymentGateway::where('name','marcadopago')->first();
        $decoded = json_decode($paypal_credential_from_database->credentials);

        $client_id = $decoded->client_id ?? '';
        $client_secret = $decoded->client_secret ?? '';

        $marcadopago = XgPaymentGateway::marcadopago();
        $marcadopago->setClientId($client_id);
        $marcadopago->setClientSecret($client_secret);
        $marcadopago->setEnv(true);

        $payment_data = $marcadopago->ipn_response();
        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $this->update_database($payment_data['order_id'], $payment_data['transaction_id']);
            $this->send_order_mail($payment_data['order_id']);
            $order_id = Str::random(6) . $payment_data['order_id']. Str::random(6);
            return redirect()->route('tenant.frontend.order.payment.success',$order_id);
        }
        return $this->cancel_page();
    }


    private function update_database($order_id, $transaction_id)
    {
        PaymentLogs::where('id', $order_id)->update([
            'transaction_id' => $transaction_id,
            'payment_status' => 'complete',
            'updated_at' => Carbon::now()
        ]);

    }
    public function send_order_mail($order_id)
    {
        $package_details = PaymentLogs::where('id', $order_id)->firstOrFail();

        $all_fields = [];//unserialize($order_details->custom_fields,['class'=> false]);
        //unset($all_fields['package']);
        $all_attachment = [];//unserialize($order_details->attachment,['class'=> false]);
        $order_mail = get_static_option('order_page_form_mail') ?? get_static_option('tenant_site_global_email');

        Mail::to($order_mail)->send(new PlaceOrderTenant($all_fields, $all_attachment, $package_details));
        Mail::to($package_details->email ?? '')->send(new PlaceOrderTenant($all_fields, $all_attachment, $package_details));

        try {
            Mail::to($order_mail)->send(new PlaceOrderTenant($all_fields, $all_attachment, $package_details));
            Mail::to($package_details->email ?? '')->send(new PlaceOrderTenant($all_fields, $all_attachment, $package_details));
        }catch (\Exception $e){
            return redirect()->back()->with(['type'=> 'danger', 'msg' => $e->getMessage()]);
        }
    }

 private function get_filtered_data_from_request($option_value = null, $request = null){
        $all_attachment = [];
        $all_quote_form_fields = (array) json_decode($option_value);
        $all_field_type = isset($all_quote_form_fields['field_type']) ? (array) $all_quote_form_fields['field_type'] : [];
        $all_field_name = isset($all_quote_form_fields['field_name']) ? $all_quote_form_fields['field_name'] : [];
        $all_field_required = isset($all_quote_form_fields['field_required'])  ? (object) $all_quote_form_fields['field_required'] : [];
        $all_field_mimes_type = isset($all_quote_form_fields['mimes_type']) ? (object) $all_quote_form_fields['mimes_type'] : [];
        //get field details from, form request
        $all_field_serialize_data = $request->all();
        unset($all_field_serialize_data['_token']);
        if (!empty($all_field_name)) {
            foreach ($all_field_name as $index => $field) {
                $is_required = !empty($all_field_required) && property_exists($all_field_required, $index) ? $all_field_required->$index : '';
                $mime_type = !empty($all_field_mimes_type) && property_exists($all_field_mimes_type, $index) ? $all_field_mimes_type->$index : '';
                $field_type = isset($all_field_type[$index]) ? $all_field_type[$index] : '';
                if (!empty($field_type) && $field_type == 'file') {
                    unset($all_field_serialize_data[$field]);
                }
                $validation_rules = !empty($is_required) ? 'required|' : '';
                $validation_rules .= !empty($mime_type) ? $mime_type : '';
                //validate field
                $this->validate($request, [
                    $field => $validation_rules
                ]);
                if ($field_type == 'file' && $request->hasFile($field)) {
                    $filed_instance = $request->file($field);
                    $file_extenstion = $filed_instance->getClientOriginalExtension();
                    $attachment_name = 'attachment-' . Str::random(32) . '-' . $field . '.' . $file_extenstion;
                    $filed_instance->move('landlord/uploads/attachment/applicant', $attachment_name);
                    $all_attachment[$field] = 'landlord/uploads/attachment/applicant/' . $attachment_name;
                }
            }
        }
        return [
            'all_attachment' => $all_attachment,
            'field_data' => $all_field_serialize_data
        ];
    }
}
