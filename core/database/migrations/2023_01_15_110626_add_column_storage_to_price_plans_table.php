<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::table('price_plans', function (Blueprint $table) {
            $table->bigInteger('storage_permission_feature')->nullable();
        });
    }

    public function down()
    {
        Schema::table('price_plans', function (Blueprint $table) {
            $table->dropColumn('storage_permission_feature');
        });
    }
};
