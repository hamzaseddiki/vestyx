<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeddingPaymentLogsTable extends Migration
{

    public function up()
    {
        Schema::create('wedding_payment_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('package_id');
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('email');
            $table->string('package_name');
            $table->double('package_price');
            $table->string('package_gateway');
            $table->string('status');
            $table->string('payment_status');
            $table->longText('transaction_id');
            $table->string('track');
            $table->string('manual_payment_attachment')->nullable();
            $table->timestamps();

            $table->foreign('package_id')->references('id')->on('wedding_price_plans')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('wedding_payment_logs');
    }
}
