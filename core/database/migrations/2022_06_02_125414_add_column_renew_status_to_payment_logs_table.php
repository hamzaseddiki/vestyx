<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnRenewStatusToPaymentLogsTable extends Migration
{

    public function up()
    {
        if (!Schema::hasColumn('payment_logs', 'renew_status')) {
            Schema::table('payment_logs', function (Blueprint $table) {
                $table->string('renew_status')->nullable();
            });
        }
    }

    public function down()
{
    if (!Schema::hasColumn('payment_logs', 'renew_status')){
            Schema::table('payment_logs', function (Blueprint $table) {
                $table->dropColumn('renew_status');
            });
        }
    }
}
