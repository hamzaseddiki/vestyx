<?php

namespace Modules\Product\Http\Controllers\Frontend;

use App\Actions\Tenant\ProductOrder\PaymentGatewayIpn;
use App\Helpers\Payment\PaymentGatewayCredential;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Tenant\Frontend\PricePlan;
use App\Http\Requests\CheckoutFormRequest;
use App\Http\Services\CheckoutToPaymentService;
use App\Http\Services\ProductCheckoutService;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use Modules\CountryManage\Entities\Country;
use Modules\CountryManage\Entities\State;
use Modules\Product\Entities\ProductOrder;
use Modules\TaxModule\Entities\CountryTax;
use Modules\TaxModule\Entities\StateTax;


class CheckoutPaymentController extends Controller
{

    public function checkout_page()
    {
        $cart_data = Cart::content();
        $billing_info = Auth::guard('web')->user()?->delivery_address;
        $countries = Country::where('status', 'publish')->get();
        $states = State::where('status', 'publish')->get();

        $data = get_product_shipping_tax_data($billing_info);
        $product_tax = $data['product_tax'];

        return themeView('shop.checkout.checkout_page', compact('cart_data', 'billing_info', 'countries', 'states', 'product_tax'));
    }

    public function checkout(CheckoutFormRequest $request)
    {
        $validated_data = $request->validated();
        $validated_data['checkout_type'] = $validated_data['cash_on_delivery'] === 'on' ? 'cod' : 'digital';

        $checkout_service = new ProductCheckoutService();
        $user = $checkout_service->getOrCreateUser($validated_data);
        $order_log_id = $checkout_service->createOrder($validated_data, $user);

        // Checking shipping method is selected
        if(!$order_log_id) {
            return back()->withErrors(['error' => 'Please select a shipping method']);
        }

        return CheckoutToPaymentService::checkoutToGateway(compact('order_log_id', 'validated_data')); // Sending multiple data compacting together in one array
    }

    public function paypal_ipn()
    {
        return (new PaymentGatewayIpn())->paypal_ipn();
    }

    public function paytm_ipn()
    {
        return (new PaymentGatewayIpn())->paytm_ipn();
    }

    public function mollie_ipn()
    {
        return (new PaymentGatewayIpn())->mollie_ipn();
    }

    public function stripe_ipn()
    {
        return (new PaymentGatewayIpn())->stripe_ipn();
    }

    public function razorpay_ipn()
    {
        return (new PaymentGatewayIpn())->razorpay_ipn();
    }

    public function payfast_ipn()
    {
        return (new PaymentGatewayIpn())->payfast_ipn();
    }

    public function flutterwave_ipn()
    {
        return (new PaymentGatewayIpn())->flutterwave_ipn();
    }

    public function paystack_ipn()
    {
        return (new PaymentGatewayIpn())->paystack_ipn();
    }

    public function midtrans_ipn()
    {
        return (new PaymentGatewayIpn())->midtrans_ipn();
    }

    public function cashfree_ipn()
    {
        return (new PaymentGatewayIpn())->cashfree_ipn();
    }

    public function instamojo_ipn()
    {
        return (new PaymentGatewayIpn())->instamojo_ipn();
    }

    public function marcadopago_ipn()
    {
        return (new PaymentGatewayIpn())->marcadopago_ipn();
    }

    public function squareup_ipn()
    {
        return (new PaymentGatewayIpn())->squareup_ipn();
    }

    public function cinetpay_ipn()
    {
        return (new PaymentGatewayIpn())->cinetpay_ipn();
    }

    public function paytabs_ipn()
    {
        return (new PaymentGatewayIpn())->paytabs_ipn();
    }

    public function billplz_ipn()
    {
        return (new PaymentGatewayIpn())->billplz_ipn();
    }

    public function zitopay_ipn()
    {
        return (new PaymentGatewayIpn())->zitopay_ipn();
    }

    public function toyyibpay_ipn()
    {
        return (new PaymentGatewayIpn())->toyyibpay_ipn();
    }

    public function pagali_ipn()
    {
        return (new PaymentGatewayIpn())->pagali_ipn();
    }

    public function authorizenet_ipn()
    {
        return (new PaymentGatewayIpn())->authorizenet_ipn();
    }

    public function sitesway_ipn()
    {
        return (new PaymentGatewayIpn())->sitesway_ipn();
    }

    public function kinetic_ipn()
    {
        return (new PaymentGatewayIpn())->kinetic_ipn();
    }

    public function order_payment_cancel($id)
    {
        $order_details = ProductOrder::find($id);
        return view('tenant.frontend.payment.payment-cancel')->with(['order_details' => $order_details]);
    }

    public function order_payment_cancel_static()
    {
        return view('tenant.frontend.payment.payment-cancel-static');
    }

    public function order_confirm($id)
    {
        $order_details = PricePlan::where('id', $id)->first();
        return view('tenant.frontend.pages.package.order-page')->with(['order_details' => $order_details]);
    }


    public function order_payment_success($id)
    {
        $extract_id = substr($id, 6);
        $extract_id = substr($extract_id, 0, -6);

        $payment_details = '';
        if (!empty($extract_id)) {
            $payment_details = ProductOrder::find($extract_id);
        }

        Cart::destroy();

        return themeView('ecommerce-payment.payment-success', compact('payment_details'));

    }
}
