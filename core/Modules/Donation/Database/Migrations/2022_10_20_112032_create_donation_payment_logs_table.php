<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDonationPaymentLogsTable extends Migration
{

    public function up()
    {
        
            if(!Schema::hasTable('donation_payment_logs')){
                 Schema::create('donation_payment_logs', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger('donation_id');
                    $table->unsignedBigInteger('user_id')->nullable();
                    $table->string('transaction_id')->nullable();
                    $table->string('name');
                    $table->string('email');
                    $table->double('amount');
                    $table->string('payment_gateway')->nullable();
                    $table->string('track')->nullable();
                    $table->string('manual_payment_attachment')->nullable();
                    $table->boolean('status')->default(1);
                    $table->text('note')->nullable();
                    $table->timestamps();
    
                    $table->foreign('donation_id')->references('id')->on('donations')->onDelete('cascade');
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                });
            }

    }


    public function down()
    {
        Schema::dropIfExists('donation_payment_logs');
    }
}
