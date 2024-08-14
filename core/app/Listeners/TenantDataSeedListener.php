<?php

namespace App\Listeners;

use App\Events\TenantRegisterEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;

class TenantDataSeedListener
{

    public function __construct()
    {
        //
    }

    public function handle(TenantRegisterEvent $event)
    {
        //--class=UserSeeder
        try{
            //database migrate
            $command = 'tenants:migrate --force --tenants='.$event->subdomain;
            Artisan::call($command);

        }catch(\Exception $e){
            $message = $e->getMessage();

            if(str_contains($message,'Duplicate entry')){
                if(request()->ajax()){
                    abort(500,__('Tenant database demo data already imported'));
                }
                return redirect()->route('landlord.admin.tenant')->with(['msg' => __('Tenant database demo data already imported'), 'type'=>'danger']);
            }

            if(str_contains($message,'does not exist')){
                if(request()->ajax()){
                    abort(500,__('Tenant does not exists'));
                }
                return redirect()->route('landlord.admin.tenant')->with(['msg' => __('Tenant not exists'), 'type'=>'danger']);
            }
        }

        $command = 'tenants:seed --force --tenants='.$event->subdomain;
        Artisan::call($command);
    }
}
