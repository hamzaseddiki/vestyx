<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        if(!Schema::hasColumn('payment_logs','assign_status')){
            Schema::table('payment_logs', function (Blueprint $table) {
                $table->boolean('assign_status')->default(0);
            });
        }

    }

    public function down()
    {
          if(!Schema::hasColumn('payment_logs','assign_status')){
            Schema::table('payment_logs', function (Blueprint $table) {
                $table->dropColumn('assign_status');
            });
         }
    }
};
