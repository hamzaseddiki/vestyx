<?php

namespace Modules\Wallet\Http\Services;

use App\Helpers\TenantHelper\TenantHelpers;
use App\Mail\BasicMail;
use App\Models\PaymentLogs;
use App\Models\PricePlan;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Modules\Wallet\Entities\Wallet;
use Modules\Wallet\Entities\WalletSettings;
use Modules\Wallet\Entities\WalletTenantList;

class WalletService
{
    function __construct()
    {
        if (empty(get_static_option('user_wallet')))
        {
            return back();
        }
    }
    public static function check_wallet_balance($user_id)
    {
        $user_id = $user_id ?? Auth::guard('web')->user()?->id;
        $user = User::find($user_id);

        if ($user->wallet?->walletSettings?->wallet_alert)
        {
            $wallet_balance = $user?->wallet?->balance;
            $wallet_minimum_amount = $user?->wallet?->walletSettings?->minimum_amount ?? 0;

            if ($wallet_balance <= $wallet_minimum_amount)
            {
                $email = $user->email;
                $subject = 'Wallet balance alert';
                $message = sprintf('Your wallet balance is low. Your current balance is %g'.site_currency_symbol().'', $wallet_balance);

                Mail::to($email)->send(new BasicMail($message, $subject));
            }
        }
    }

    public static function renew_package_from_wallet()
    {

        $array_index['failed'] = 0;
        $array_index['completed'] = 0;

        $failed_packages_count = 0;
        $failed_packages = [];

        $completed_packages_count = 0;
        $completed_packages = [];
        $user = Auth::guard('web')->user();
        $email = $user->email ?? '';

        $price_plans = PricePlan::select('id', 'title', 'type', 'price')->get();
        foreach ($price_plans as $plan)
        {
            $payment_logs = PaymentLogs::where(['package_id' => $plan->id, 'payment_status' => 'complete'])->latest()->get();
            foreach ($payment_logs->unique('user_id') as $log)
            {

                $user = User::find($log->user_id);
                $user_id = $user->id;
                $email = $user->email;


                $wallet_settings = WalletSettings::where('user_id', $user_id)->first();

                if (!empty($wallet_settings) && $wallet_settings->renew_package)
                {
                    $wallet_tenant_list = WalletTenantList::where('user_id', $user_id)->get();

                    foreach ($wallet_tenant_list ?? [] as $key => $tenant)
                    {

                        $expire_date = Carbon::parse($tenant->tenant?->expire_date);

                        if ($expire_date->greaterThan(now()))
                        {
                            continue;
                        }


                        $wallet_balance = Wallet::where('user_id', $user_id)->first()->balance;

                        if($plan->price <= $wallet_balance)
                        {
                            if (($wallet_balance - $plan->price) < 0)
                            {
                                continue;
                            }

                            \DB::beginTransaction();
                            try {
                                self::renew_package($tenant, $log, $plan, $user);
                                \DB::commit();

                                $completed_packages['tenant_id'][$array_index['completed']] = $tenant->tenant_id;
                                $completed_packages['price_plan_title'][$array_index['completed']] = $plan->title;
                                $completed_packages['price_plan_price'][$array_index['completed']] = $plan->price;
                                $array_index['completed']++;
                                $completed_packages_count++;
                            } catch (\Exception $exception)
                            {
                                \DB::rollBack();
                                return $exception;
                            }
                        } else {
                            $failed_packages['tenant_id'][$array_index['failed']] = $tenant->tenant_id;
                            $failed_packages['price_plan_title'][$array_index['failed']] = $plan->title;
                            $failed_packages['price_plan_price'][$array_index['failed']] = $plan->price;
                            $array_index['failed']++;
                            $failed_packages_count++;
                        }
                    }
                }
            }
        }


        self::failed_renewal($failed_packages_count, $failed_packages, $email);
        self::renew_package_email($completed_packages_count, $completed_packages, $email);
        self::check_wallet_balance($user->id);
    }

    private static function failed_renewal($failed_packages_count, $failed_packages, $email)
    {
        if ($failed_packages_count > 0)
        {
            $unit = $failed_packages_count > 1 ? __('Some') : __('One');

            $subject = 'Package renewal failed';
            $message = '<h4>'.$unit.' '.__('of your package is failed to renew due to low balance in wallet').'</h4></br>';

            $i=1;
            for ($key=0; $key<count(current($failed_packages)); $key++)
            {
                $message .= '<span>'.$i++.'</span>. <span>'.$failed_packages['tenant_id'][$key].'</span> - <span>'.$failed_packages['price_plan_title'][$key].'</span> - <span>'.$failed_packages['price_plan_price'][$key].site_currency_symbol().'</span></br>';
            }
            $message .= '<br><p>'.__('Please deposit balance to continue using the renewal feature').'</p>';

            Mail::to($email)->send(new BasicMail($message, $subject));
        }
    }

    private static function renew_package($tenant, $last_payment_log, $used_price_plan, $user)
    {
        //todo work on here
        $tenantHelper = TenantHelpers::init()->setTenantId($tenant->id)->setPackage($used_price_plan)->setPaymentLog($last_payment_log)->setUser($user);

        $package_start_date = $tenantHelper->getStartDate();
        $package_expire_date = $tenantHelper->getExpiredDate();
        $tenantHelper->paymentLogUpdate([
            'email' => $last_payment_log->email,
            'name' => $last_payment_log->name,
            'package_name' => $used_price_plan->getTranslation('title',get_user_lang()),
            'package_price' => $used_price_plan->price,
            'package_gateway' => 'wallet',
            'package_id' => $used_price_plan->id,
            'user_id' => $tenant->user_id ?? null,
            'tenant_id' => $tenant->tenant_id ?? null,
            'status' => 'complete',
            'payment_status' => 'complete',
            'renew_status' => is_null($last_payment_log->renew_status) ? 1 : $last_payment_log->renew_status + 1,
            'is_renew' => 1,
            'track' => Str::random(10) . Str::random(10),
            'updated_at' => Carbon::now(),
            'start_date' => $tenantHelper->getStartDate(),
            'expire_date' =>  $tenantHelper->getExpiredDate()
        ],true);

        $tenantHelper->tenantUpdate([
            'renew_status' => $renew_status = is_null($tenant->renew_status) ? 0 : $tenant->renew_status+1,
            'is_renew' => $renew_status == 0 ? 0 : 1,
            'start_date' => $tenantHelper->getStartDate(),
            'expire_date' => $tenantHelper->getExpiredDate(),
        ],true);

        $last_balance = Wallet::where('user_id', $user->id)->first();
        Wallet::where('user_id', $user->id)->update([
            'balance' => $last_balance->balance - $used_price_plan->price
        ]);
    }

    private static function update_tenant($payment_id)
    {
        $payment_log = PaymentLogs::where('id', $payment_id)->first();
        $tenant = Tenant::find($payment_log->tenant_id);

        \DB::table('tenants')->where('id', $tenant->id)->update([
            'renew_status' => $renew_status = is_null($tenant->renew_status) ? 0 : $tenant->renew_status+1,
            'is_renew' => $renew_status == 0 ? 0 : 1,
            'start_date' => $payment_log->start_date,
            'expire_date' => get_plan_left_days($payment_log->package_id, $tenant->expire_date)
        ]);
    }

    private static function renew_package_email($completed_packages_count, $completed_packages, $email)
    {
        if ($completed_packages_count > 0)
        {
            $unit = $completed_packages_count > 1 ? ['are', 's'] : ['is', ''];

            $subject = 'Package auto renewed';
            $message = '<h4>'.__('Your package'.$unit[1].' '.$unit[0].' renewed successfully using wallet balance').'</h4></br>';

            $i=1;
            for ($key=0; $key<count(current($completed_packages)); $key++)
            {
                $message .= '<span>'.$i++.'</span>. <span>'.$completed_packages['tenant_id'][$key].'</span> - <span>'.$completed_packages['price_plan_title'][$key].'</span> - <span>'.$completed_packages['price_plan_price'][$key].site_currency_symbol().'</span></br>';
            }
            $message .= '<br><p>'.__('To check it out please visit the website').'</p>';

            Mail::to($email)->send(new BasicMail($message, $subject));
        }
    }
}
