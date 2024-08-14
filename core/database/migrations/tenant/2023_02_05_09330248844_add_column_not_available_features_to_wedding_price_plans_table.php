<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        if(!Schema::hasColumn('wedding_price_plans','not_available_features')){
            Schema::table('wedding_price_plans', function (Blueprint $table) {
                $table->longText('not_available_features')->nullable();
            });
        }

    }

    public function down()
    {
        if(!Schema::hasColumn('wedding_price_plans','not_available_features')){
            Schema::table('wedding_price_plans', function (Blueprint $table) {
                $table->dropColumn('not_available_features');
            });
        }
    }
};
