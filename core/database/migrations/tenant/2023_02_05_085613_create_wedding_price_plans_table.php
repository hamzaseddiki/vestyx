<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWeddingPricePlansTable extends Migration
{

    public function up()
    {
        Schema::create('wedding_price_plans', function (Blueprint $table) {
            $table->id();
            $table->longText('title');
            $table->longText('features');
            $table->double('price');
            $table->string('is_popular')->nullable();
            $table->bigInteger('status')->default(1);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('wedding_price_plans');
    }
}
