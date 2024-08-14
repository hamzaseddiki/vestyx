<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubAppointmentsTable extends Migration
{

    public function up()
    {
        Schema::create('sub_appointments', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->longText('description')->nullable();
            $table->string('image')->nullable();
            $table->double('price');
            $table->bigInteger('person')->default(1);
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('sub_appointments');
    }
}
