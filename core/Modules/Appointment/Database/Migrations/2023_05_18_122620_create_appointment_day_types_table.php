<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppointmentDayTypesTable extends Migration
{

    public function up()
    {
        Schema::create('appointment_day_types', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('appointment_day_types');
    }
}
