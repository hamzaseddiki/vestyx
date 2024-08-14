<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{

    protected $except = [
        //Landlord
        '/paytm-ipn',
        '/cashfree-ipn',
        '/payfast-ipn',
        '/cinetpay-ipn',
        '/zitopay-ipn',
        '/paytabs-ipn',
        '/toyyibpay-ipn',
        '/pagali-ipn',
         'siteways-ipn',

        //Tenant
        '/payment-donation/paytm-ipn',
        '/payment-donation/razorpay-ipn',
        '/payment-donation/payfast-ipn',
        '/payment-donation/cashfree-ipn',
        '/payment-donation/cinetpay-ipn',
        '/payment-donation/paytabs-ipn',
        '/payment-donation/zitopay-ipn',
        '/payment-donation/toyyibpay-ipn',
        '/payment-donation/pagali-ipn',
        '/payment-donation/siteways-ipn',

        '/payment-event/paytm-ipn',
        '/payment-event/razorpay-ipn',
        '/payment-event/payfast-ipn',
        '/payment-event/cashfree-ipn',
        '/payment-event/cinetpay-ipn',
        '/payment-event/paytabs-ipn',
        '/payment-event/zitopay-ipn',
        '/payment-event/toyyibpay-ipn',
        '/payment-event/pagali-ipn',
        '/payment-event/siteways-ipn',

        '/payment-job/paytm-ipn',
        '/payment-job/razorpay-ipn',
        '/payment-job/payfast-ipn',
        '/payment-job/cashfree-ipn',
        '/payment-job/cinetpay-ipn',
        '/payment-job/paytabs-ipn',
        '/payment-job/zitopay-ipn',
        '/payment-job/toyyibpay-ipn',
        '/payment-job/pagali-ipn',
        '/payment-job/siteways-ipn',

        '/shop/payment-product/paytm-ipn',
        '/shop/payment-product/razorpay-ipn',
        '/shop/payment-product/payfast-ipn',
        '/shop/payment-product/cashfree-ipn',
        '/shop/payment-product/cinetpay-ipn',
        '/shop/payment-product/paytabs-ipn',
        '/shop/payment-product/zitopay-ipn',
        '/shop/payment-product/toyyibpay-ipn',
        '/shop/payment-product/pagali-ipn',
        '/shop/payment-product/siteways-ipn',

        '/wedding/paytm-ipn',
        '/wedding/razorpay-ipn',
        '/wedding/payfast-ipn',
        '/wedding/cashfree-ipn',
        '/wedding/cinetpay-ipn',
        '/wedding/paytabs-ipn',
        '/wedding/zitopay-ipn',
        '/wedding/toyyibpay-ipn',
        '/wedding/pagali-ipn',
        '/wedding/siteways-ipn',

        '/appointment-payment/paytm-ipn',
        '/appointment-payment/razorpay-ipn',
        '/appointment-payment/payfast-ipn',
        '/appointment-payment/cashfree-ipn',
        '/appointment-payment/cinetpay-ipn',
        '/appointment-payment/paytabs-ipn',
        '/appointment-payment/zitopay-ipn',
        '/appointment-payment/toyyibpay-ipn',
        '/appointment-payment/pagali-ipn',
        '/appointment-payment/siteways-ipn',


        '/hotel-booking-payment/paytm-ipn',
        '/hotel-booking-payment/razorpay-ipn',
        '/hotel-booking-payment/payfast-ipn',
        '/hotel-booking-payment/cashfree-ipn',
        '/hotel-booking-payment/cinetpay-ipn',
        '/hotel-booking-payment/paytabs-ipn',
        '/hotel-booking-payment/zitopay-ipn',
        '/hotel-booking-payment/toyyibpay-ipn',
        '/hotel-booking-payment/pagali-ipn',
        '/hotel-booking-payment/siteways-ipn',
    ];
}
