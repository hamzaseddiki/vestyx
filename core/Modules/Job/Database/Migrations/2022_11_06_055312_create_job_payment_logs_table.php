<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobPaymentLogsTable extends Migration
{

    public function up()
    {
        if(!Schema::hasTable('job_payment_logs')){     
            Schema::create('job_payment_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('job_id');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('name');
                $table->string('email');
                $table->string('phone');
                $table->double('amount')->nullable();
                $table->string('payment_gateway')->nullable();
                $table->string('transaction_id')->nullable();
                $table->string('track')->nullable();
                $table->string('manual_payment_attachment')->nullable();
                $table->string('resume')->nullable();
                $table->text('comment')->nullable();
                $table->boolean('status')->default(1);
                $table->boolean('payable_status')->default(0);
    
                $table->foreign('job_id')->references('id')->on('jobs')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('job_payment_logs');
    }
}
