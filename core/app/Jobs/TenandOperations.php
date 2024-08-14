<?php

namespace App\Jobs;

use App\Helpers\Payment\DatabaseUpdateAndMailSend\LandlordPricePlanAndTenantCreate;
use App\Helpers\TenantHelper\TenantGenerateHelper;
use AWS\CRT\Log;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class TenandOperations implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $tenant;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tenant)
    {
        $this->tenant = $tenant;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $tenantGenerateHelper = TenantGenerateHelper::init($this->tenant);
            if ($tenantGenerateHelper->userExists()){
                if (!$tenantGenerateHelper->databaseExists() && $tenantGenerateHelper->hasPermissoinToCreateDatabase()){
                    $tenantGenerateHelper->createDatabase();
                    $tenantGenerateHelper->runMigration();
                    $tenantGenerateHelper->runSeeder();
                }elseif($tenantGenerateHelper->databaseExists() && $tenantGenerateHelper->hasPermissoinToCreateDatabase()){
                    $tenantGenerateHelper->runMigration();
                }elseif(!$tenantGenerateHelper->hasPermissoinToCreateDatabase() && !$tenantGenerateHelper->databaseExists()){
                    //exception store
                    LandlordPricePlanAndTenantCreate::store_exception($this->tenant->id,'database create/migrate failed','system did not have permission to create database automatically, you have to create database manually and assign it.',$tenantGenerateHelper->domainExists() ? 1 : 0);
                }
            }else{
                $tenantGenerateHelper->deleteTenant();
                return;
            }

            if (!$tenantGenerateHelper->domainExists()){
                $tenantGenerateHelper->createDomain();
            }
        }catch (\Exception $e){
            //if issue is related to the mysql database engine,
            LandlordPricePlanAndTenantCreate::store_exception($this->tenant->id,'database create/migrate failed',$e->getMessage(),0);
        }

    }
}
