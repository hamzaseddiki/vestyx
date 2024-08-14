<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePricePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_plans', function (Blueprint $table) {
            $table->id();
            $table->longText('title');
            $table->longText('subtitle')->nullable();
            $table->unsignedBigInteger('type')->nullable();
            $table->unsignedBigInteger('status')->default(0);
            $table->double('price');
            $table->longText('faq')->nullable();
            $table->integer('blog_permission_feature')->nullable();
            $table->integer('page_permission_feature')->nullable();
            $table->boolean('has_trial')->default(false);
            $table->unsignedInteger('trial_days')->nullable();
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
        Schema::dropIfExists('price_plans');
    }
}
