<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('website_instructions', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->longText('description');
            $table->longText('repeater_data')->nullable();
            $table->string('image')->nullable();
            $table->bigInteger('status')->default(1);
            $table->text('unique_key')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('website_instructions');
    }
};
