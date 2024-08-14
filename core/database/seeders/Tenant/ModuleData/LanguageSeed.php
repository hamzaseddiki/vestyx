<?php

namespace Database\Seeders\Tenant\ModuleData;

use App\Helpers\SeederHelpers\JsonDataModifier;
use App\Models\Language;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class LanguageSeed extends Seeder
{
    public static function run()
    {
        if(!Schema::hasTable('languages')){
            Schema::create('languages', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('name');
                $table->string('slug',10);
                $table->unsignedBigInteger('direction')->default(0);
                $table->unsignedBigInteger('status')->default(0);
                $table->unsignedBigInteger('default')->default(0);
                $table->timestamps();
            });
        }


        //todo insert data from seeder json file
        $event_cat = new JsonDataModifier('', 'language');
        $data = $event_cat->getColumnData([
            "id",
            "name",
            "slug",
            "direction",
            "status",
            "default",
        ]);
        Language::insert($data);
    }
}
