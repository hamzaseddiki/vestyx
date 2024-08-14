<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::table('tenant_activity_log', function (Blueprint $table) {
            $table->string('unique_key')->nullable();
        });
    }

    public function down()
    {
        Schema::table('tenant_activity_log', function (Blueprint $table) {
            $table->dropColumn('unique_key');
        });
    }
};
