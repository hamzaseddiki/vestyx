<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        if(!Schema::hasColumn('price_plans','zero_price')){
            Schema::table('price_plans', function (Blueprint $table) {
                $table->string('zero_price')->nullable();
            });
        }


    }

    public function down()
    {
        if(!Schema::hasColumn('price_plans','zero_price')){
            Schema::table('price_plans', function (Blueprint $table) {
                $table->dropColumn('zero_price');
            });
        }

    }
};
