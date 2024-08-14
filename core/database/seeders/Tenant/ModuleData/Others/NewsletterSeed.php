<?php

namespace Database\Seeders\Tenant\ModuleData\Others;

use App\Helpers\ImageDataSeedingHelper;
use App\Helpers\SanitizeInput;
use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewsletterSeed extends Seeder
{

    public static function execute()
    {
        DB::statement("INSERT INTO `newsletters` (`id`, `email`, `token`, `verified`, `created_at`, `updated_at`)
VALUES
	(9,'dd@ff','VC7M4Pss0b6GrRz7pAnAwVOOyHZgdJ1g','0','2022-10-30 13:25:36','2022-10-30 13:25:36'),
	(10,'saa@ddd','vsIeivLmRPoTQG8AX8joAfNILYixTewG','0','2022-10-30 13:26:00','2022-10-30 13:26:00'),
	(11,'gg@ff','3ONGK9fdJPyctRuvxr9cWto2foPVTlmq','0','2022-10-30 13:26:59','2022-10-30 13:26:59'),
	(12,'ggsws@dd','WrkPSLhkCWfcRNrF2YOH3rUGAYWZv1mM','0','2022-10-30 13:27:23','2022-10-30 13:27:23'),
	(13,'sdfgdsf@ff',NULL,'0','2022-10-30 13:29:43','2022-10-30 13:29:43'),
	(14,'dd@ffds',NULL,'0','2022-11-10 12:10:25','2022-11-10 12:10:25')");
    }
}
