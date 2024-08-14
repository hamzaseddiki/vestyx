<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_log_histories', function (Blueprint $table) {
            $table->id();
            $table->integer('package_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('coupon_id')->nullable();
            $table->double('coupon_discount')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('package_name')->nullable();
            $table->double('package_price')->nullable();
            $table->string('package_gateway')->nullable();
            $table->string('status')->nullable();
            $table->string('payment_status')->nullable();
            $table->longText('transaction_id')->nullable();
            $table->string('manual_payment_attachment')->nullable();
            $table->string('track')->nullable();
            $table->bigInteger('is_renew')->nullable();
            $table->bigInteger('renew_status')->nullable();
            $table->bigInteger('assign_status')->nullable();
            $table->string('theme')->nullable();
            $table->string('start_date')->nullable();
            $table->string('expire_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_log_histories');
    }
};
