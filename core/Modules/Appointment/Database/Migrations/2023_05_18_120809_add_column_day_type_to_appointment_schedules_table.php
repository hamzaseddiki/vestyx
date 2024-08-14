<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnDayTypeToAppointmentSchedulesTable extends Migration
{

    public function up()
    {
        if(!Schema::hasColumn('appointment_schedules','day_type')) {
            Schema::table('appointment_schedules', function (Blueprint $table) {
                $table->text('day_type')->nullable();
            });
        }
    }


    public function down()
    {
        if(!Schema::hasColumn('appointment_schedules','day_type')) {
            Schema::table('appointment_schedules', function (Blueprint $table) {
                $table->dropColumn('day_type');
            });
        }
    }
}
