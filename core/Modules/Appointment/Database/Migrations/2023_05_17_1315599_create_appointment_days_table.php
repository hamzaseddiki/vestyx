<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppointmentDaysTable extends Migration
{
    public function up()
    {
        Schema::create('appointment_days', function (Blueprint $table) {
            $table->id();
            $table->string('day');
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('appointment_days');
    }
}
