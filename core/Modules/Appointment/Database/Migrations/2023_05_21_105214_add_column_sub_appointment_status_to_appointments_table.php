<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnSubAppointmentStatusToAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('appointments','sub_appointment_status')){
            Schema::table('appointments', function (Blueprint $table) {
                $table->string('sub_appointment_status')->nullable();
            });
        }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(!Schema::hasColumn('appointments','sub_appointment_status')) {
            Schema::table('appointments', function (Blueprint $table) {
                $table->dropColumn('sub_appointment_status');
            });
        }
    }
}
