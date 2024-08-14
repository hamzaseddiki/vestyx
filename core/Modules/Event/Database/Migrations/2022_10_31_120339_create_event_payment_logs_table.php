<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventPaymentLogsTable extends Migration
{

    public function up()
    {
        
    if(!Schema::hasTable('event_payment_logs')){  
    
            Schema::create('event_payment_logs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('event_id');
                $table->unsignedBigInteger('user_id')->nullable();
                $table->string('transaction_id')->nullable();
                $table->string('name');
                $table->string('email');
                $table->string('phone');
                $table->string('address')->nullable();
                $table->bigInteger('ticket_qty');
                $table->double('amount');
                $table->string('payment_gateway')->nullable();
                $table->string('track')->nullable();
                $table->string('manual_payment_attachment')->nullable();
                $table->boolean('status')->default(1);
                $table->timestamps();
    
                $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    
            });
         }
    }


    public function down()
    {
        Schema::dropIfExists('event_payment_logs');
    }
}
