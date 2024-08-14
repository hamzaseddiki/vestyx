<?php

namespace Database\Seeders\Tenant\ModuleData\Blog;

use App\Helpers\ImageDataSeedingHelper;
use App\Helpers\SanitizeInput;
use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Blog\Entities\Blog;
use Modules\Blog\Entities\BlogCategory;

class AdvertisementSeed extends Seeder
{

    public static function execute()
    {

      DB::statement("INSERT INTO `advertisements` (`id`, `type`, `size`, `image`, `slot`, `embed_code`, `redirect_url`, `click`, `impression`, `status`, `created_at`, `updated_at`, `title`)
VALUES
	(2,'image','950*160','321','4379939651',NULL,'#',2,0,1,'2021-10-17 14:33:13','2023-01-16 07:19:03','Travel Advertisement Google Adsense'),
	(3,'scripts','728*90',NULL,NULL,'','',0,5,1,'2021-10-17 14:35:32','2021-11-14 11:13:21','Fashion Advertisement  Custom Scripts'),
	(8,'image','950*160','324',NULL,NULL,'#',0,1,1,'2021-11-03 11:55:16','2023-01-16 07:18:56','Festival'),
	(9,'image','950*200','333',NULL,NULL,'#',10,109,1,'2021-11-13 19:22:52','2023-01-16 14:06:08','Advertisement Two'),
	(10,'image','950*200','321',NULL,NULL,'#',10,141,1,'2021-11-13 19:24:17','2023-01-16 14:03:42','Advertisement Three'),
	(11,'scripts','250*1110',NULL,NULL,'','',0,0,1,'2021-11-14 16:30:32','2021-11-14 16:31:45','Script Test 2'),
	(13,'image','300*600','319',NULL,NULL,'#',0,0,1,'2023-01-16 11:06:33','2023-01-16 11:06:33','Sidebar Add')");
    }



}
