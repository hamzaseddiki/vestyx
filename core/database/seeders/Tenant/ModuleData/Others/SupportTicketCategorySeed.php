<?php

namespace Database\Seeders\Tenant\ModuleData\Others;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SupportTicketCategorySeed
{

    public static function execute()
    {

        if(!Schema::hasTable('support_departments')){
            Schema::create('support_departments', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('status')->nullable();
                $table->timestamps();
            });
        }

        DB::statement("INSERT INTO `support_departments` (`id`, `name`, `status`, `created_at`, `updated_at`)
VALUES
	(1,'{\"en_US\":\"Login\"}','1','2022-11-22 08:53:22','2022-11-22 08:53:22'),
	(2,'{\"en_US\":\"Install issue\"}','1','2022-11-22 08:53:29','2022-11-22 08:53:29'),
	(3,'{\"en_US\":\"General Issue\"}','1','2022-11-22 08:53:40','2022-11-22 08:53:40'),
	(4,'{\"en_US\":\"Other\"}','1','2022-11-22 08:53:46','2022-11-22 08:53:46')");
    }

}
