<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        if(Schema::hasTable('price_plans')){

            Schema::table('price_plans', function (Blueprint $table) {
                if(!Schema::hasColumn('price_plans','product_create_permission')){
                    $table->integer('product_create_permission')->nullable();
                }
                if(!Schema::hasColumn('price_plans','product_create_permission')){
                    $table->integer('campaign_create_permission')->nullable();
                }

            });
        }

    }

    public function down()
    {
        if(Schema::hasTable('price_plans')){
            Schema::table('price_plans', function (Blueprint $table) {
                if(!Schema::hasColumn('price_plans','product_create_permission')){
                    $table->dropColumn('product_create_permission');
                }
                if(!Schema::hasColumn('price_plans','product_create_permission')){
                    $table->dropColumn('campaign_create_permission');
                }


            });
        }
    }
};
