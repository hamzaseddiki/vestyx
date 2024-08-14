<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppointmentPaymentAdditionalLogsTable extends Migration
{

    public function up()
    {
        Schema::create('appointment_payment_additional_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('appointment_payment_log_id');
            $table->unsignedBigInteger('appointment_id');
            $table->unsignedBigInteger('sub_appointment_id')->nullable();
            $table->double('appointment_price');
            $table->double('sub_appointment_price')->nullable();
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('appointment_payment_additional_logs');
    }
}
