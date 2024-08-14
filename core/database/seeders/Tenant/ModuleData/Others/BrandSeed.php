<?php

namespace Database\Seeders\Tenant\ModuleData\Others;

use App\Helpers\ImageDataSeedingHelper;
use App\Helpers\SanitizeInput;
use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BrandSeed extends Seeder
{
    public static function execute()
    {
        DB::statement("INSERT INTO `brands` (`url`, `image`, `status`, `created_at`, `updated_at`)
VALUES
	('#','77',1,'2022-09-03 11:56:25','2022-09-03 11:58:56'),
	('#','76',1,'2022-09-03 11:56:36','2022-09-03 11:56:36'),
	('#','75',1,'2022-09-03 11:56:43','2022-09-03 11:56:43'),
	('#','74',1,'2022-09-03 11:58:21','2022-09-03 11:58:21'),
	('#','73',1,'2022-09-03 11:58:35','2022-09-03 11:58:35'),
	('#','72',1,'2022-09-03 11:58:43','2022-09-03 11:58:43'),
	('s','72',1,'2022-09-03 12:32:36','2022-09-03 12:32:36')");
    }
}
