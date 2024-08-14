<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::table('tenant_activity_log', function (Blueprint $table) {
            $table->text('user_ip')->nullable();
            $table->text('user_agent')->nullable();
        });
    }

    public function down()
    {
        Schema::table('tenant_activity_log', function (Blueprint $table) {
            $table->dropColumn('user_ip');
            $table->dropColumn('user_agent');
        });
    }
};
