<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppointmentTaxesTable extends Migration
{

    public function up()
    {
        Schema::create('appointment_taxes', function (Blueprint $table) {
            $table->id();
            $table->integer('appointment_id');
            $table->string('tax_type');
            $table->integer('tax_amount')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('appointment_taxes');
    }
}
