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
        if(!Schema::hasTable('coupon_logs')){
            Schema::create('coupon_logs', function (Blueprint $table) {
                $table->id();
                $table->integer('log_id')->nullable();
                $table->integer('coupon_id')->nullable();
                $table->integer('user_id')->nullable();
                $table->timestamps();
            });
        }


    }

    public function down()
    {
        Schema::dropIfExists('coupon_logs');
    }
};
