<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Wallet\Http\Services\WalletService;

class PackageAutoRenewUsingWallet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'package:auto-renew';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        WalletService::renew_package_from_wallet();
    }
}
