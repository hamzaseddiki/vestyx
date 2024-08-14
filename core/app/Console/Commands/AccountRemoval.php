<?php

namespace App\Console\Commands;

use App\Events\TenantCronjobEvent;
use App\Events\TenantNotificationEvent;
use App\Helpers\TenantHelper\TenantHelpers;
use App\Jobs\SendAccountRemoveMailJob;
use App\Mail\BasicMail;
use App\Models\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use function Clue\StreamFilter\fun;

class AccountRemoval extends Command
{

    protected $signature = 'account:remove';
    protected $description = 'Command description';


    public function __construct()
    {
        parent::__construct();
    }


    public function handle()
    {
        //todo run a query to get all the tenant then run migrate one by one...
        Tenant::with(['user','payment_log','domain','payment_log.package'])
            ->whereHas('payment_log',function ($q){
                $q->select(['email','name','package_id','user_id','tenant_id','status','payment_status','start_date','expire_date']);
                $q->where('payment_status','complete');
                $q->orWhere('status','trial');
            })->whereHas('domain')
            ->whereNotNull('user_id')
            ->whereNull('cancel_type')
            ->chunk(20,function ($tenants){

                $count = 0 ;
                foreach ($tenants as $tenant){

                    //todo all operation will be here
                    //add option to make it through offload which is Jobs..

                    $tenantHelper = TenantHelpers::init()
                        ->setTenantId($tenant->getTenantKey())
                        ->setTenant($tenant)
                        ->setUser($tenant->user)
                        ->setPaymentLog($tenant->payment_log)
                        ->setPackage($tenant->payment_log->package);

                    //todo check how many days it left for

                    $day_list = json_decode(get_static_option('tenant_account_delete_notify_mail_days')) ?? [];
                    $remove_day = get_static_option('account_remove_day_within_expiration');
                    rsort($day_list);

                    $cron_qty = 0;
                    foreach ($day_list as $day){
                        $expired_date = $tenantHelper->getExpiredDate(true);
                        if (is_null($expired_date)){
                            //info:: if both is null continue the loop
                            continue;
                        }

                        $startDate = Carbon::today();
                        $notification_date = \Carbon\Carbon::parse($expired_date)->subDays($day); //30 - 5 , 25
                        $compareDays = $notification_date->lt($startDate); // 25 < 26

                        $expired_date_with_extend = \Carbon\Carbon::parse($expired_date)->addDays($remove_day); //Extra added days before account remove
                        $diff = $startDate->diff($expired_date_with_extend);  //For Check the difference if $expire_date_with_extend is already expired

                        if ($compareDays && !$diff->invert){
                            $days_reaming = $startDate->diffInDays(\Carbon\Carbon::parse($expired_date_with_extend));

                            SendAccountRemoveMailJob::dispatch($days_reaming,$tenantHelper->getPaymentLog());

                            //Cronjob Store Event
                            $event_data = [
                                'id' =>  $tenantHelper->getPaymentLog()->id,
                                'title' =>  __('Account Removal Cronjob'),
                                'type' =>  'account_removal',
                                'running_qty' => $cron_qty + 1,
                            ];
                            event(new TenantCronjobEvent($event_data));
                            //Cronjob Store Event
                            break;
                        }

                    }
                    $count++;
                    if ($count >= 3) {
                        break;
                    }
                }

            });
        return Command::SUCCESS;
    }
}
