<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::table('price_plans', function (Blueprint $table) {
            $table->integer('service_permission_feature')->nullable();
            $table->integer('donation_permission_feature')->nullable();
            $table->integer('job_permission_feature')->nullable();
            $table->integer('event_permission_feature')->nullable();
            $table->integer('knowledgebase_permission_feature')->nullable();
        });
    }

    public function down()
    {
        Schema::table('price_plans', function (Blueprint $table) {
            //
        });
    }
};
