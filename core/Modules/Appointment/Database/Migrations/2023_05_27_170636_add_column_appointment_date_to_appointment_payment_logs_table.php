<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnAppointmentDateToAppointmentPaymentLogsTable extends Migration
{

    public function up()
    {
        if(!Schema::hasColumn('appointment_payment_logs','appointment_date')){
            Schema::table('appointment_payment_logs', function (Blueprint $table) {
                $table->string('appointment_date')->after('phone');
            });
        }
    }

    public function down()
    {
        if(!Schema::hasColumn('appointment_payment_logs','appointment_date')) {
            Schema::table('appointment_payment_logs', function (Blueprint $table) {
                $table->dropColumn('appointment_date');
            });
        }
    }
}
