<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDonationsTable extends Migration
{

    public function up()
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->longText('description')->nullable();
            $table->string('amount');
            $table->string('raised')->nullable();
            $table->boolean('status')->default(0);
            $table->string('image')->nullable();
            $table->string('image_gallery')->nullable();
            $table->text('slug')->nullable();
            $table->text('excerpt')->nullable();
            $table->bigInteger('creator_id')->unsigned();
            $table->string('created_by')->nullable();
            $table->bigInteger('category_id')->unsigned();
            $table->longText('faq')->nullable();
            $table->string('deadline')->nullable();
            $table->string('popular')->nullable();
            $table->bigInteger('views')->default(0);
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('donations');
    }
}
