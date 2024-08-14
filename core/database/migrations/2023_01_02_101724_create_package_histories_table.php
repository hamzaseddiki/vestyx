<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('package_histories', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_domain');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('payment_log_id');
            $table->boolean('trial_status')->default(1);
            $table->integer('trial_qty')->default(0);
            $table->boolean('zero_price_status')->default(1);
            $table->integer('zero_package_qty')->default(0);
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('package_histories');
    }
};
