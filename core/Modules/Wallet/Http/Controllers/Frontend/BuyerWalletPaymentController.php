<?php

namespace Modules\Wallet\Http\Controllers\Frontend;

use App\Helpers\FlashMsg;
use App\Helpers\Payment\PaymentGatewayCredential;
use App\Mail\BasicMail;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Wallet\Entities\Wallet;
use Modules\Wallet\Entities\WalletHistory;
use Modules\Wallet\Http\Services\WalletService;

class BuyerWalletPaymentController extends Controller
{
    function __construct()
    {
        abort_if(empty(get_static_option('user_wallet')), 404);
    }

    protected function cancel_page()
    {
        return redirect()->route('buyer.wallet.deposit.payment.cancel.static');
    }
    public function paypal_ipn_for_wallet()
    {
        $paypal = PaymentGatewayCredential::get_paypal_credential();
        $payment_data = $paypal->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function paytm_ipn_for_wallet()
    {
        $paytm = PaymentGatewayCredential::get_paytm_credential();
        $payment_data = $paytm->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function flutterwave_ipn_for_wallet()
    {
        $flutterwave = PaymentGatewayCredential::get_flutterwave_credential();
        $payment_data = $flutterwave->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function stripe_ipn_for_wallet()
    {
        $stripe = PaymentGatewayCredential::get_stripe_credential();
        $payment_data = $stripe->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function razorpay_ipn_for_wallet()
    {
        $razorpay = PaymentGatewayCredential::get_razorpay_credential();
        $payment_data = $razorpay->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function paystack_ipn_for_wallet()
    {
        $paystack = PaymentGatewayCredential::get_paystack_credential();
        $payment_data = $paystack->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function payfast_ipn_for_wallet()
    {
        $payfast = PaymentGatewayCredential::get_payfast_credential();
        $payment_data = $payfast->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function mollie_ipn_for_wallet()
    {
        $mollie = PaymentGatewayCredential::get_mollie_credential();
        $payment_data = $mollie->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function midtrans_ipn_for_wallet()
    {
        $midtrans = PaymentGatewayCredential::get_midtrans_credential();
        $payment_data = $midtrans->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function cashfree_ipn_for_wallet()
    {
        $cashfree = PaymentGatewayCredential::get_cashfree_credential();
        $payment_data = $cashfree->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function instamojo_ipn_for_wallet()
    {
        $instamojo = PaymentGatewayCredential::get_instamojo_credential();
        $payment_data = $instamojo->ipn_response();
        return $this->common_ipn_data($payment_data);
    }
    public function marcadopago_ipn_for_wallet()
    {
        $marcadopago = PaymentGatewayCredential::get_marcadopago_credential();
        $payment_data = $marcadopago->ipn_response();
        return $this->common_ipn_data($payment_data);
    }
    public function squareup_ipn_for_wallet()
    {
        $squareup = PaymentGatewayCredential::get_squareup_credential();
        $payment_data = $squareup->ipn_response();
        return $this->common_ipn_data($payment_data);
    }
    public function cinetpay_ipn_for_wallet()
    {
        $cinetpay = PaymentGatewayCredential::get_cinetpay_credential();
        $payment_data = $cinetpay->ipn_response();
        return $this->common_ipn_data($payment_data);
    }
    public function paytabs_ipn_for_wallet()
    {
        $paytabs = PaymentGatewayCredential::get_paytabs_credential();
        $payment_data = $paytabs->ipn_response();
        return $this->common_ipn_data($payment_data);
    }
    public function billplz_ipn_for_wallet()
    {
        $billplz = PaymentGatewayCredential::get_billplz_credential();
        $payment_data = $billplz->ipn_response();
        return $this->common_ipn_data($payment_data);
    }
    public function zitopay_ipn_for_wallet()
    {
        $zitopay = PaymentGatewayCredential::get_zitopay_credential();
        $payment_data = $zitopay->ipn_response();
        return $this->common_ipn_data($payment_data);
    }
    public function toyyibpay_ipn_for_wallet()
    {
        $toyyibpay = PaymentGatewayCredential::get_toyyibpay_credential();
        $payment_data = $toyyibpay->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    public function kinetic_ipn_for_wallet()
    {
        $kineticpay = PaymentGatewayCredential::get_kinetic_credential();
        $payment_data = $kineticpay->ipn_response();
        return $this->common_ipn_data($payment_data);
    }

    private function common_ipn_data($payment_data)
    {
        if (isset($payment_data['status']) && $payment_data['status'] === 'complete'){
            $order_id = $payment_data['order_id'];
            $history_id = session()->get('history_id');
            $this->update_database($order_id, $payment_data['transaction_id'],$history_id);
            $this->send_jobs_mail($order_id);
            $new_order_id =  $order_id;

            return redirect()->route('landlord.user.wallet.history')->with(['type' => 'success', 'msg' => 'Your wallet successfully credited']);
        }

        return $this->cancel_page();
    }

    public function send_jobs_mail($last_deposit_id)
    {
        if(empty($last_deposit_id)){
            return redirect()->route('landlord.user.wallet.history')->with(FlashMsg::explain('danger', 'Something went wrong. Try again after sometimes'));
        }
        //Send order email to buyer
        try {
            $message_body = __('Hello an user just deposit to his wallet.').'</br>'.'<span class="verify-code">'.__('Deposit ID: ').$last_deposit_id.'</span>';
            \Mail::to(get_static_option('site_global_email'))->send(new BasicMail($message_body, __('Deposit Confirmation')));

        } catch (\Exception $e) {
            return redirect()->back()->with(FlashMsg::explain('danger', $e->getMessage()));
        }
    }

    private function update_database($last_deposit_id, $transaction_id)
    {
        $deposit_details = WalletHistory::find($last_deposit_id);

        DB::beginTransaction();
        try {
            WalletHistory::where('id', $last_deposit_id)->update([
                'payment_status' => 'complete',
                'transaction_id' => $transaction_id,
                'status' => 1,
            ]);

            $get_balance_from_wallet = Wallet::where('user_id',$deposit_details->user_id)->first();
            Wallet::where('user_id', $deposit_details->user_id)->update([
                'balance' => $get_balance_from_wallet->balance + $deposit_details->amount,
            ]);

            WalletService::check_wallet_balance($deposit_details->user_id);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            return redirect()->route('landlord.user.wallet.history')->with(FlashMsg::explain('danger', 'Something went wrong. Please try again after some while'));
        }
    }
}
