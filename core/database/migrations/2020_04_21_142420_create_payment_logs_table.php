<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('email')->nullable();
            $table->string('name')->nullable();
            $table->string('package_name')->nullable();
            $table->string('package_price')->nullable();
            $table->string('package_gateway')->nullable();
            $table->string('package_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('status')->nullable();
            $table->string('payment_status')->nullable();
            $table->longText('transaction_id')->nullable();
            $table->longText('attachments')->nullable();
            $table->longText('custom_fields')->nullable();
            $table->string('track')->nullable();
            $table->bigInteger('renew_status')->nullable();
            $table->string('theme')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payment_logs');
    }
}
