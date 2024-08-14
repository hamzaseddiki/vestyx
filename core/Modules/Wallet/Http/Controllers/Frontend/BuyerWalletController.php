<?php

namespace Modules\Wallet\Http\Controllers\Frontend;


use App\Events\TenantNotificationEvent;
use App\Helpers\FlashMsg;
use App\Helpers\ThemeMetaData;
use App\Helpers\Payment\PaymentGatewayCredential;
use App\Mail\BasicMail;
use App\Models\PaymentGateway;
use App\Models\User;
use App\Traits\ManualPaymentGatewayHelper;
use FontLib\Table\Type\name;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Modules\Wallet\Entities\Wallet;
use Modules\Wallet\Entities\WalletHistory;
use Modules\Wallet\Entities\WalletSettings;
use Modules\Wallet\Entities\WalletTenantList;
use Modules\Wallet\Http\Services\WalletService;
use Illuminate\Support\Str;
use Xgenious\Paymentgateway\Facades\XgPaymentGateway;

class BuyerWalletController extends Controller
{
    use ManualPaymentGatewayHelper;
    private const CANCEL_ROUTE = 'landlord.user.wallet.deposit.payment.cancel.static';

    private float $total;
    private string $title;
    private string $description;
    private int $last_deposit_id;

    function __construct()
    {
        abort_if(empty(get_static_option('user_wallet')), 404);
    }

    public function deposit_payment_cancel_static()
    {
        return view('wallet::frontend.buyer.payment-cancel-static');
    }

    public function deposit_payment_success()
    {
        return back()->with(['type' => 'success', 'Your Deposit is Successful']);
    }

    public function wallet_history()
    {
        $wallet_histories = WalletHistory::latest()
            ->where('user_id', Auth::guard('web')->user()->id)
            ->whereIn('payment_status', ['complete', 'pending'])
            ->paginate(10);
        $balance = Wallet::select('balance')->where('user_id', Auth::guard('web')->user()->id)->first();

        return view('wallet::frontend.buyer.wallet-history', compact('wallet_histories', 'balance'));
    }

    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|integer|min:10|max:5000',
        ]);
        if ($request->selected_payment_gateway === 'manual_payment_') {
            $request->validate([
                'manual_payment_image' => 'required|mimes:jpg,jpeg,png,svg,pdf'
            ],
                [
                    'The manual payment image must be a file of type - jpg, jpeg, png, svg and pdf.'
                ]);

            $file_extention = $request->manual_payment_image->getClientOriginalExtension();
            $imageTypes = ['jpeg', 'png', 'jpg', 'svg', 'pdf'];
            if (!in_array($file_extention, $imageTypes)) {
                return back()->withErrors('Please insert a valid image attachment');
            }
        }

        //deposit amount
        $this->total = $request->amount;

        $user = Auth::guard('web')->user();
        $user_id = $user->id;
        $name = $user->name;
        $email = $user->email;
        if ($this->checkIsManualPayment()) {
            $payment_status = 'pending';
        } else {
            $payment_status = '';
        }

        $buyer = Wallet::where('user_id', $user_id)->first();
        if (empty($buyer)) {
            Wallet::create([
                'user_id' => $user_id,
                'balance' => 0,
                'status' => 0,
            ]);
        }

        $deposit = WalletHistory::create([
            'user_id' => $user_id,
            'amount' => $this->total,
            'payment_gateway' => $request->selected_payment_gateway,
            'payment_status' => $payment_status,
            'status' => 1,
        ]);


        //Notification Event
            $event_data = ['id' =>  $deposit->id, 'title' =>  __('Wallet deposited'), 'type' =>  'wallet_deposit',];
            event(new TenantNotificationEvent($event_data));
        //Notification Event

        $this->last_deposit_id = $deposit->id;
        $this->title = __('Deposit To Wallet');
        $this->description = sprintf(__('Order id #%1$d Email: %2$s, Name: %3$s'), $this->last_deposit_id, $email, $name);

        if ($this->checkIsManualPayment()) {
            if ($request->hasFile('manual_payment_image')) {
                $manual_payment_image = $request->manual_payment_image;
                $img_ext = $manual_payment_image->extension();

                $manual_payment_image_name = 'manual_attachment_' . time() . '.' . $img_ext;
                if (in_array($img_ext, ['jpg', 'jpeg', 'png', 'svg', 'pdf'])) {
                    $manual_image_path = 'assets/landlord/uploads/deposit_payment_attachments/';
                    $manual_payment_image->move($manual_image_path, $manual_payment_image_name);
                    WalletHistory::where('id', $this->last_deposit_id)->update([
                        'manual_payment_image' => $manual_payment_image_name
                    ]);
                } else {
                    return back()->with(['msg' => __('image type not supported'), 'type' => 'danger']);
                }
            }

            try {
                $message_body = __('Hello a buyer just deposit to his wallet. Please check and confirm') . '</br>' . '<span class="verify-code">' . __('Deposit ID: ') . $this->last_deposit_id . '</span>';
                Mail::to(get_static_option('site_global_email'))->send(new BasicMail($message_body, __('Deposit Confirmation')));
                Mail::to($email)->send(new BasicMail(__('Manual deposit success. Your wallet will credited after admin approval #') . $this->last_deposit_id, __('Deposit Confirmation')));
            } catch (\Exception $e) {
                //
            }

            return back()->with(['type' => 'success', 'msg' => 'Manual deposit success. Your wallet will credited after admin approval']);

        } else {
            return $this->payment_with_gateway($request->selected_payment_gateway);
        }
    }

    public function payment_with_gateway($payment_gateway_name)
    {
        try {
            $gateway_function = 'get_' . $payment_gateway_name . '_credential';
            $gateway = PaymentGatewayCredential::$gateway_function();

            $redirect_url = $gateway->charge_customer(
                $this->common_charge_customer_data($payment_gateway_name)
            );

            session()->put('order_id', $this->last_deposit_id);
            return $redirect_url;
        } catch (\Exception $e) {
            return back()->with(['msg' => $e->getMessage(), 'type' => 'danger']);
        }
    }

    public function common_charge_customer_data($payment_gateway_name)
    {
        $user = Auth::guard('web')->user();
        $email = $user->email;
        $name = $user->name;

        return [
            'amount' => $this->total,
            'title' => $this->title,
            'description' => $this->description,
            'ipn_url' => route('buyer.' . strtolower($payment_gateway_name) . '.ipn.wallet'),
            'order_id' => $this->last_deposit_id,
            'track' => \Str::random(36),
            'cancel_url' => route(self::CANCEL_ROUTE, $this->last_deposit_id),
            'success_url' => route('landlord.user.wallet.deposit.payment.success'),
            'email' => $email,
            'name' => $name,
            'payment_type' => 'deposit',
        ];
    }

    public function wallet_settings()
    {
        $balance = Wallet::select('balance')->where('user_id', Auth::guard('web')->user()->id)->first();
        $settings = WalletSettings::where('user_id', Auth::guard('web')->user()->id)->first();
        return view('wallet::frontend.buyer.wallet-settings', compact('settings', 'balance'));
    }

    public function wallet_settings_update(Request $request)
    {
        $request->validate([
            'renewal_using_wallet' => 'nullable',
            'tenants_list' => '' . $request->renewal_using_wallet != null ? 'required' : 'nullable' . '',
            'wallet_balance_alert' => 'nullable',
            'minimum_alert_amount' => '' . $request->wallet_balance_alert != null ? 'required' : 'nullable' . ''
        ], [
            'tenants_list.required' => 'The subdomains are required',
        ]);

        \DB::beginTransaction();
        try {
            $user = Auth::guard('web')->user();
            $settings = WalletSettings::updateOrCreate(
                [
                    'user_id' => $user->id
                ],
                [
                    'user_id' => $user->id,
                    'renew_package' => $request->renewal_using_wallet == 'on',
                    'wallet_alert' => $request->wallet_balance_alert == 'on',
                    'minimum_amount' => $request->minimum_alert_amount
                ]
            );

            $tenant_list = WalletTenantList::where('user_id', $user->id)->get();
            if (count($tenant_list) > 0)
            {
                WalletTenantList::where('user_id', $user->id)->delete();
            }

            foreach ($request->tenants_list ?? [] as $tenant) {
                WalletTenantList::insert([
                    'user_id' => $settings->user_id,
                    'tenant_id' => $tenant,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }

            \DB::commit();
            return back()->with(FlashMsg::update_succeed('Wallet Settings'));
        } catch (\Exception $exception)
        {
            \DB::rollBack();
            return back()->with($exception->getMessage());
        }
    }
}
