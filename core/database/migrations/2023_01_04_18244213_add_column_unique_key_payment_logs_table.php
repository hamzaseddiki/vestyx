<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        if(!Schema::hasColumn('payment_logs','unique_key')){
            Schema::table('payment_logs', function (Blueprint $table) {
                $table->string('unique_key')->nullable();
            });
        }

    }


    public function down()
    {
            Schema::table('payment_logs', function (Blueprint $table) {
                $table->dropColumn('unique_key');
            });

    }
};
