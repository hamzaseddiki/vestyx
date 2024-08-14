<?php

namespace App\Actions\Tenant\ProductOrder;

use App\Enums\PaymentRouteEnum;
use App\Helpers\Payment\PaymentGatewayCredential;
use App\Mail\ProductOrderEmail;
use App\Mail\ProductOrderEmailAdmin;
use App\Mail\ProductOrderManualEmail;
use App\Models\Admin;
use Modules\Product\Entities\ProductOrder;
use Illuminate\Support\Facades\Mail;

class PaymentGatewayIpn
{
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

    private function common_ipn_data($payment_data)
    {
        if (isset($payment_data['status']) && $payment_data['status'] === 'complete') {
            (new self())->send_order_mail($payment_data['order_id']);
            $order_id = wrap_random_number($payment_data['order_id']);

            ProductOrder::find($payment_data['order_id'])->update([
                'payment_status' => 'success'
            ]);

            return redirect()->route(PaymentRouteEnum::SUCCESS_ROUTE, $order_id);
        }

        return redirect()->route(PaymentRouteEnum::STATIC_CANCEL_ROUTE);
    }

    public function send_order_mail($order_id)
    {
        $order_details = \Modules\Product\Entities\ProductOrder::where('id', $order_id)->firstOrFail();
        $order_mail = get_static_option('order_page_form_mail') ?? get_static_option('tenant_site_global_email');

        try {
            //To User/Customer
            if ($order_details->checkout_type === 'digital')
            {
                Mail::to($order_mail)->send(new ProductOrderEmail($order_details));
            } else {
                Mail::to($order_mail)->send(new ProductOrderManualEmail($order_details));
            }

            // To Admin
            $admin_email = get_static_option('order_receiving_email') ?? get_static_option('tenant_site_global_email');
            if ($admin_email == null)
            {
                $admin = \App\Models\Admin::whereHas("roles", function($q){
                    $q->where("name", "Super Admin");
                })->first();
                $admin_email = $admin->email;
            }

            Mail::to($admin_email)->send(new ProductOrderEmailAdmin($order_details));

        } catch (\Exception $e) {
            return redirect()->back()->with(['type' => 'danger', 'msg' => $e->getMessage()]);
        }
    }
}
