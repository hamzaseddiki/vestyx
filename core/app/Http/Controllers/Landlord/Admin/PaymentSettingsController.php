<?php

namespace App\Http\Controllers\Landlord\Admin;

use App\Facades\GlobalLanguage;
use App\Helpers\ResponseMessage;
use App\Http\Controllers\Controller;
use App\Mail\BasicMail;
use App\Models\Language;
use App\Models\Page;
use App\Models\PaymentGateway;
use App\Models\StaticOption;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Psr\Http\Message\UriInterface;
use Spatie\Sitemap\SitemapGenerator;

class PaymentSettingsController extends Controller
{
    const BASE_PATH = 'landlord.admin.general-settings.payment-settings.';

    public function currency_settings()
    {
        return view(self::BASE_PATH . 'currencies');
    }

    public function update_currency_settings(Request $request)
    {
        $this->validate($request, [
            'site_global_currency' => 'nullable|string|max:191',
            'site_currency_symbol_position' => 'nullable|string|max:191',
            'site_default_payment_gateway' => 'nullable|string|max:191',
        ]);

        $global_currency = get_static_option('site_global_currency');

        $save_data = [
            'site_global_currency',
            'site_global_payment_gateway',
            'site_usd_to_ngn_exchange_rate',
            'site_euro_to_ngn_exchange_rate',
            'site_currency_symbol_position',
            'site_default_payment_gateway',
            'currency_amount_type_status',
            'site_custom_currency_symbol',
            'coupon_apply_status',

            'site_' . strtolower($global_currency) . '_to_idr_exchange_rate',
            'site_' . strtolower($global_currency) . '_to_inr_exchange_rate',
            'site_' . strtolower($global_currency) . '_to_ngn_exchange_rate',
            'site_' . strtolower($global_currency) . '_to_zar_exchange_rate',
            'site_' . strtolower($global_currency) . '_to_brl_exchange_rate',
            'site_' . strtolower($global_currency) . '_to_myr_exchange_rate',
            'site_' . strtolower($global_currency) . '_to_usd_exchange_rate',
        ];

        foreach ($save_data as $item) {
            update_static_option($item, $request->$item);
        }

        Artisan::call('cache:clear');
        return redirect()->back()->with([
            'msg' => __('Currency Settings Updated..'),
            'type' => 'success'
        ]);
    }


    public function paypal_settings()
    {
        $gateway = PaymentGateway::where('name', 'paypal')->first();
        if (is_null($gateway)) {
            $this->seedPaymentGatewayData('paypal');
        }
        return view(self::BASE_PATH . 'payment-data', compact('gateway'));
    }

    public function paytm_settings()
    {
        $gateway = PaymentGateway::where('name', 'paytm')->first();
        if (is_null($gateway)) {
            $this->seedPaymentGatewayData('paytm');
        }
        return view(self::BASE_PATH . 'payment-data', compact('gateway'));
    }

    public function stripe_settings()
    {
        $gateway = PaymentGateway::where('name', 'stripe')->first();
        if (is_null($gateway)) {
            $this->seedPaymentGatewayData('stripe');
        }
        return view(self::BASE_PATH . 'payment-data', compact('gateway'));
    }

    public function razorpay_settings()
    {
        $gateway = PaymentGateway::where('name', 'razorpay')->first();
        if (is_null($gateway)) {
            $this->seedPaymentGatewayData('razorpay');
        }
        return view(self::BASE_PATH . 'payment-data', compact('gateway'));
    }

    public function paystack_settings()
    {
        $gateway = PaymentGateway::where('name', 'paystack')->first();
        if (is_null($gateway)) {
            $this->seedPaymentGatewayData('paystack');
        }
        return view(self::BASE_PATH . 'payment-data', compact('gateway'));
    }

    public function mollie_settings()
    {
        $gateway = PaymentGateway::where('name', 'mollie')->first();
        if (is_null($gateway)) {
            $this->seedPaymentGatewayData('mollie');
        }
        return view(self::BASE_PATH . 'payment-data', compact('gateway'));
    }

    public function payfast_settings()
    {
        $gateway = PaymentGateway::where('name', 'payfast')->first();
        if (is_null($gateway)) {
            $this->seedPaymentGatewayData('payfast');
        }
        return view(self::BASE_PATH . 'payment-data', compact('gateway'));
    }

    public function midtrans_settings()
    {
        $gateway = PaymentGateway::where('name', 'midtrans')->first();
        if (is_null($gateway)) {
            $this->seedPaymentGatewayData('midtrans');
        }
        return view(self::BASE_PATH . 'payment-data', compact('gateway'));
    }

    public function cashfree_settings()
    {
        $gateway = PaymentGateway::where('name', 'cashfree')->first();
        if (is_null($gateway)) {
            $this->seedPaymentGatewayData('cashfree');
        }
        return view(self::BASE_PATH . 'payment-data', compact('gateway'));
    }

    public function instamojo_settings()
    {
        $gateway = PaymentGateway::where('name', 'instamojo')->first();
        if (is_null($gateway)) {
            $this->seedPaymentGatewayData('instamojo');
        }
        return view(self::BASE_PATH . 'payment-data', compact('gateway'));
    }

    public function marcadopago_settings()
    {
        $gateway = PaymentGateway::where('name', 'marcadopago')->first();
        if (is_null($gateway)) {
            $this->seedPaymentGatewayData('marcadopago');
        }
        return view(self::BASE_PATH . 'payment-data', compact('gateway'));
    }

    public function zitopay_settings()
    {
        $gateway = PaymentGateway::where('name', 'zitopay')->first();
        if (is_null($gateway)) {
            $this->seedPaymentGatewayData('zitopay');
        }
        return view(self::BASE_PATH . 'payment-data', compact('gateway'));
    }

    public function squareup_settings()
    {
        $gateway = PaymentGateway::where('name', 'squareup')->first();
        if (is_null($gateway)) {
            $this->seedPaymentGatewayData('squareup');
        }
        return view(self::BASE_PATH . 'payment-data', compact('gateway'));
    }

    public function cinetpay_settings()
    {
        $gateway = PaymentGateway::where('name', 'cinetpay')->first();
        if (is_null($gateway)) {
            $this->seedPaymentGatewayData('cinetpay');
        }
        return view(self::BASE_PATH . 'payment-data', compact('gateway'));
    }

    public function paytabs_settings()
    {
        $gateway = PaymentGateway::where('name', 'paytabs')->first();
        if (is_null($gateway)) {
            $this->seedPaymentGatewayData('paytabs');
        }
        return view(self::BASE_PATH . 'payment-data', compact('gateway'));
    }

    public function billplz_settings()
    {
        $gateway = PaymentGateway::where('name', 'billplz')->first();
        if (is_null($gateway)) {
            $this->seedPaymentGatewayData('billplz');
        }
        return view(self::BASE_PATH . 'payment-data', compact('gateway'));
    }


    public function manual_payment_settings()
    {

        $gateway = PaymentGateway::where('name', 'manual_payment_')->first();

        if (is_null($gateway)) {
            $this->seedPaymentGatewayData('manual_payment_');
        }

        return view(self::BASE_PATH . 'payment-data', compact('gateway'));
    }
    public function bank_transfer_settings()
    {
        $gateway = PaymentGateway::where('name', 'bank_transfer')->first();
        if (is_null($gateway)) {
            $this->seedPaymentGatewayData('bank_transfer');
        }
        return view(self::BASE_PATH . 'payment-data', compact('gateway'));
    }



    public function flutterwave_settings()
    {
        $gateway = PaymentGateway::where('name', 'flutterwave')->first();
        if (is_null($gateway)) {
            $this->seedPaymentGatewayData('flutterwave');
        }
        return view(self::BASE_PATH . 'payment-data', compact('gateway'));
    }

    public function toyyibpay_settings()
    {
        $gateway = PaymentGateway::where('name', 'toyyibpay')->first();
        if (is_null($gateway)) {
            $this->seedPaymentGatewayData('toyyibpay');
        }
        return view(self::BASE_PATH . 'payment-data', compact('gateway'));
    }

    public function pagali_settings()
    {
        $gateway = PaymentGateway::where('name', 'pagali')->first();
        if (is_null($gateway)) {
            $this->seedPaymentGatewayData('pagali');
        }
        return view(self::BASE_PATH . 'payment-data', compact('gateway'));
    }

    public function authorizenet_settings()
    {
        $gateway = PaymentGateway::where('name', 'authorizenet')->first();
        if (is_null($gateway)) {
            $this->seedPaymentGatewayData('authorizenet');
        }
        return view(self::BASE_PATH . 'payment-data', compact('gateway'));
    }

    public function sitesway_settings()
    {
        $gateway = PaymentGateway::where('name', 'sitesway')->first();
        if (is_null($gateway)) {
            $this->seedPaymentGatewayData('sitesway');
        }
        return view(self::BASE_PATH . 'payment-data', compact('gateway'));
    }



    public function kinetic_settings()
    {
        $gateway = PaymentGateway::where('name', 'kinetic')->first();
        if (is_null($gateway)) {
            $this->seedPaymentGatewayData('kinetic');
        }
        return view(self::BASE_PATH . 'payment-data', compact('gateway'));
    }


    public function chargilypay_settings()
    {
        $gateway = PaymentGateway::where('name', 'chargilypay')->first();
        if (is_null($gateway)) {
            $this->seedPaymentGatewayData('chargilypay');
        }
        return view(self::BASE_PATH . 'payment-data', compact('gateway'));
    }


    private function seedPaymentGatewayData($payment_gateway)
    {
        $data = file_get_contents('assets/tenant/page-layout/payment-gateway.json');
        $all_data_decoded = json_decode($data);
        foreach ($all_data_decoded->data as $item) {
            if ($item->name == $payment_gateway) {
                PaymentGateway::create([
                    'id' => $item->id,
                    'name' => $item->name,
                    'image' => $item->image,
                    'description' => $item->description,
                    'status' => $item->status,
                    'test_mode' => $item->test_mode,
                    'credentials' => $item->credentials,
                ]);
            }
        }
    }
    public function update_gateway_settings(Request $request)
    {

        $request->validate(['gateway' => 'required']);

        $gateway = PaymentGateway::where('name', $request->gateway)->first();

        // todo: if manual payament gatewya then save description into database
        $image_name = $gateway->name . '_logo';
        $status_name = $gateway->name . '_gateway';
        $test_mode_name = $gateway->name . '_test_mode';

        $credentials = !empty($gateway->credentials) ? json_decode($gateway->credentials) : [];
        $update_credentials = [];


        foreach ($credentials as $cred_name => $cred_val) {
            $crd_req_name = $gateway->name . '_' . $cred_name;
            $update_credentials[$cred_name] = $request->$crd_req_name;
        }

        PaymentGateway::where(['name' => $gateway->name])->update([
            'image' => $request->$image_name,
            'status' => isset($request->$status_name) ? 1 : 0,
            'test_mode' => isset($request->$test_mode_name) ? 1 : 0,
            'credentials' => json_encode($update_credentials)
        ]);


        Artisan::call('cache:clear');

        return redirect()->back()->with([
            'msg' => __('Payment Settings Updated..'),
            'type' => 'success'
        ]);
    }
}
