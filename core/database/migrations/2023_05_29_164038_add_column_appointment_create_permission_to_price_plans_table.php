<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('price_plans','appointment_permission_feature')){
            Schema::table('price_plans', function (Blueprint $table) {
                $table->integer('appointment_permission_feature')->nullable();
            });
        }
    }

    public function down()
    {
        if(!Schema::hasColumn('price_plans','appointment_permission_feature')) {
            Schema::table('price_plans', function (Blueprint $table) {
                $table->dropColumn('appointment_permission_feature');
            });
        }
    }
};
