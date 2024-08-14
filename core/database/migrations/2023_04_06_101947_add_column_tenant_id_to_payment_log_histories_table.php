<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
       if(!Schema::hasColumn('payment_log_histories','tenant_id')){
           Schema::table('payment_log_histories', function (Blueprint $table) {
               $table->string('tenant_id')->nullable();
           });
       }
    }


    public function down()
    {
        if(!Schema::hasColumn('payment_log_histories','tenant_id')) {
            Schema::table('payment_log_histories', function (Blueprint $table) {
                $table->dropColumn('tenant_id');
            });
        }
    }
};
