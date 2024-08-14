<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnValidityToPaymentLogsTable extends Migration
{

    public function up()
    {
        Schema::table('payment_logs', function (Blueprint $table) {
            $table->string('start_date')->nullable();
            $table->string('expire_date')->nullable();
        });
    }

    public function down()
    {
        Schema::table('payment_logs', function (Blueprint $table) {
            $table->dropColumn('start_date');
            $table->dropColumn('expire_date');
        });
    }
}
