<?php

namespace App\Console\Commands;

use App\Helpers\TenantHelper\TenantGenerateHelper;
use App\Models\PaymentLogs;
use App\Models\Tenant;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class WebsiteHealthChecker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'websiteHealthChecker:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check website necessary settings enabled or not';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        //todo:: set data for database

        //todo:: create a test tenant, run all health test then delete that tenant.
        $tenant = Tenant::create(['id' => Str::uuid()->toString()]);
        $tenantGenerateHelper = TenantGenerateHelper::init($tenant);
        //create payment log
        $paymentLog = PaymentLogs::create([
            'tenant_id' => $tenant->getTenantKey(),
            'payment_status' => 'complete',
            'status' => 'complete',
        ]);
        if ($tenantGenerateHelper->hasPermissoinToCreateDatabase() && $tenantGenerateHelper->databaseExists()){
            $tenantGenerateHelper->runSeeder();
            if (!$tenantGenerateHelper->domainExists()){
                $tenantGenerateHelper->createDomain();
            }
        }

        if ($tenantGenerateHelper->hasPermissoinToCreateDatabase() && !$tenantGenerateHelper->databaseExists()){
            $tenantGenerateHelper->runMigration();
            $tenantGenerateHelper->createDomain();
            $tenantGenerateHelper->runSeeder();
        }

        //database create permission
        update_static_option('website_has_permission_to_create_database',($tenantGenerateHelper->hasPermissoinToCreateDatabase() ? 'yes' : 'no'));

        $tenant_url = \request()->getScheme().'://'.$tenantGenerateHelper->getDomain()->domain;

        try {
            //wildcard subdomain check
            $respnose = Http::withOptions(['verify' => false])->get($tenant_url);
            update_static_option('website_wildcard_subdomain_working',$respnose->status() === 200 ? 'yes' : 'no');
            //wildcard ssl check
            update_static_option('website_wildcard_subdomain_ssl_working',\request()->getScheme() === 'https' ? 'yes' : 'no');

        }catch (\Exception $e){
            update_static_option('website_wildcard_subdomain_working','no');
            update_static_option('website_wildcard_subdomain_ssl_working','no');
        }

        //cron job
        update_static_option('website_cron_job', 'yes');


        $tenantGenerateHelper->deleteTenant();
        $paymentLog->delete();

        return Command::SUCCESS;
    }
}
