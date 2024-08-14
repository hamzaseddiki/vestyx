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
        if(!Schema::hasColumns('tenant_activity_log',['batch_uuid','event'])){
            Schema::table('tenant_activity_log', function (Blueprint $table) {
                $table->uuid('batch_uuid')->nullable();
                $table->string('event')->nullable();
            });
        }

    }

    public function down()
    {
        if(!Schema::hasColumns('tenant_activity_log',['batch_uuid','event'])) {
            Schema::table('tenant_activity_log', function (Blueprint $table) {
                $table->dropColumn('batch_uuid');
                $table->dropColumn('event');
            });
        }
    }
};
