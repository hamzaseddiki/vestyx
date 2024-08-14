<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppointmentPaymentLogsTable extends Migration
{

    public function up()
    {
        Schema::create('appointment_payment_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('appointment_id');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('appointment_time');
            $table->double('appointment_price');
            $table->string('coupon_type')->nullable();
            $table->string('coupon_code')->nullable();
            $table->double('coupon_discount')->nullable();
            $table->double('tax_amount')->nullable();
            $table->double('subtotal');
            $table->double('total_amount');
            $table->string('payment_gateway');
            $table->string('status');
            $table->string('payment_status');
            $table->text('transaction_id')->nullable();
            $table->string('manual_payment_attachment')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('appointment_payment_logs');
    }
}
