<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnUserIdToAppointmentPaymentLogsTable extends Migration
{

    public function up()
    {
        if(!Schema::hasColumn('appointment_payment_logs','user_id')){
            Schema::table('appointment_payment_logs', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->after('id');
            });
        }
    }

    public function down()
    {
        if(!Schema::hasColumn('appointment_payment_logs','user_id')) {
            Schema::table('appointment_payment_logs', function (Blueprint $table) {
                $table->dropColumn('user_id');
            });
        }
    }
}
