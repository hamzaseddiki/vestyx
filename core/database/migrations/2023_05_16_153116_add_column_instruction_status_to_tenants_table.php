<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if(!Schema::hasColumn('tenants','instruction_status')){
            Schema::table('tenants', function (Blueprint $table) {
                $table->bigInteger('instruction_status')->default(1);
            });
        }

    }

    public function down()
    {
        if(!Schema::hasColumn('tenants','instruction_status')) {
            Schema::table('tenants', function (Blueprint $table) {
                $table->dropColumn('instruction_status');
            });
        }
    }
};
