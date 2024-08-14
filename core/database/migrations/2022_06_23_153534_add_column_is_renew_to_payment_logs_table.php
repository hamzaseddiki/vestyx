<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnIsRenewToPaymentLogsTable extends Migration
{

    public function up()
    {
        Schema::table('payment_logs', function (Blueprint $table) {
            $table->bigInteger('is_renew')->nullable();
        });
    }

    public function down()
    {
        Schema::table('payment_logs', function (Blueprint $table) {
            $table->dropColumn('is_renew');
        });
    }
}
