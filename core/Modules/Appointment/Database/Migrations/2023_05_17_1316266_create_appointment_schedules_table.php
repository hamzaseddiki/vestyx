<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppointmentSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('appointment_schedules')){
            Schema::create('appointment_schedules', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('day_id');
                $table->string('time');
                $table->string('allow_multiple')->nullable();
                $table->boolean('status')->default(1);
                $table->timestamps();
            });
        }

    }

    public function down()
    {
        Schema::dropIfExists('appointment_shedules');
    }
}
