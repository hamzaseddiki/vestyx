<?php

namespace App\Traits;

use App\Helpers\Payment\PaymentGatewayCredential;

trait PaymentLogIpn
{
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
        try{
            $payment_data = $toyyibpay->ipn_response();
            return $this->common_ipn_data($payment_data);
        }catch(\Exception $e){
            return self::go_home_page();
        }

    }

    public function pagali_ipn()
    {
        $pagali_ipn = PaymentGatewayCredential::get_pagali_credential();
        try{
            $payment_data = $pagali_ipn->ipn_response();
            return $this->common_ipn_data($payment_data);
        }catch(\Exception $e){
            return self::go_home_page();
        }

    }

    public function authorizenet_ipn()
    {
        $authorize_ipn = PaymentGatewayCredential::get_authorizenet_credential();
        try{
            $payment_data = $authorize_ipn->ipn_response();
            return $this->common_ipn_data($payment_data);
        }catch(\Exception $e){

            return self::go_home_page();
        }

    }

    public function sitesway_ipn()
    {
        $sitesway_ipn = PaymentGatewayCredential::get_sitesway_credential();
        try{
            $payment_data = $sitesway_ipn->ipn_response();
            return $this->common_ipn_data($payment_data);
        }catch(\Exception $e){

            return self::go_home_page();
        }

    }

    public function kinetic_ipn()
    {
        $kinetic_ipn = PaymentGatewayCredential::get_kinetic_credential();
        try{
            $payment_data = $kinetic_ipn->ipn_response();
            return $this->common_ipn_data($payment_data);
        }catch(\Exception $e){
            return self::go_home_page();
        }
    }

}
