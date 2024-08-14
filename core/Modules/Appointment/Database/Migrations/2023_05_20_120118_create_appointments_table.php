<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppointmentsTable extends Migration
{
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->longText('description');
            $table->string('image')->nullable();
            $table->double('price');
            $table->bigInteger('person')->default(1);
            $table->bigInteger('views')->default(0);
            $table->string('is_popular')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('appointments');
    }
}
