<?php

namespace Database\Seeders\Tenant\ModuleData\Others;

use App\Helpers\ImageDataSeedingHelper;
use App\Helpers\SanitizeInput;
use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TopbarInfoSeed extends Seeder
{
    public function run()
    {
        $this->execute();
    }

    private function execute()
    {
        DB::statement("");
    }
}
