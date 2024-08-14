<?php

namespace App\Console\Commands;

use App\Events\TenantCronjobEvent;
use App\Helpers\TenantHelper\TenantHelpers;
use App\Jobs\SendPackageExpireEmailJob;
use App\Mail\BasicMail;
use App\Models\PaymentLogs;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PackageExpireCommand extends Command
{

    protected $signature = 'package:expire';
    protected $description = 'Command description';
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        \Log::info("Cron is working fine!");

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

                    $day_list = json_decode(get_static_option('package_expire_notify_mail_days')) ?? [];
                    rsort($day_list);

                    $cron_qty = 0;
                    foreach ($day_list as $day) {

//                        this two date not same
//                        $expired_date_from_tenant_helper = $tenantHelper->getExpiredDate();
//                        $expired_date_from_tenant = $tenant->expire_date;

                        $expired_date = $tenant->expire_date;

                        if (is_null($expired_date)){
                            //info:: if tenant expired date is null, get expired date from payment lo
                            $expired_date = $tenantHelper->getPaymentLog()->expire_date;
                        }

                        if (is_null($expired_date)){
                            //info:: if both is null continue the loop
                            continue;
                        }

                        $startDate = Carbon::today();
                        $notification_date = \Carbon\Carbon::parse($expired_date)->subDays($day); //30 - 5 , 25
                        $compareDays = $notification_date->lt($startDate); // 25 < 26

                        $diff = $startDate->diff($expired_date);  //For Check the difference if $expire_date_with_extend is already expired

                        if ($compareDays && !$diff->invert){
                            $days_reaming = $startDate->diffInDays(\Carbon\Carbon::parse($expired_date));

                            SendPackageExpireEmailJob::dispatch($days_reaming,$tenantHelper->getPaymentLog());

                            //Cronjob Store Event
                            $event_data = [
                                'id' =>  $tenantHelper->getPaymentLog()->id,
                                'title' =>  __('Package Expire Cronjob'),
                                'type' =>  'package_expire',
                                'running_qty' =>  $cron_qty + 1,
                            ];
                            event(new \App\Events\TenantCronjobEvent($event_data));
                            //Cronjob Store Event

                            break;
                        }

                    }

                }

            });
        return Command::SUCCESS;
    }
}
