<?php

namespace App\Console\Commands;

use App\Jobs\TenandOperations;
use App\Models\Tenant;
use AWS\CRT\Log;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class TenantCleanup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenantCleanup:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'it will check if there any tenant has without user id, also check tenant has database create or not, is it is not create it will create database for it and migrate , seed data automatically';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        Config::set("database.connections.mysql.engine","InnoDB");
        Tenant::where("cleanup",0)->latest()->chunk(50,function ($tenans){
            foreach ($tenans as $tenant){
                \DB::table("tenants")->where("id",$tenant->id)->update(["cleanup" => 1]);
                TenandOperations::dispatch($tenant);
            }
        });

        \Illuminate\Support\Facades\Log::alert('tenant cleanup started');
        return Command::SUCCESS;
    }
}
