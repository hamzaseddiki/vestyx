<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDonationActivitiesTable extends Migration
{

    public function up()
    {
        Schema::create('donation_activities', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('category_id')->unsigned();
            $table->text('title');
            $table->text('slug')->nullable();
            $table->longText('description')->nullable();
            $table->boolean('status')->default(0);
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('donation_activities');
    }
}
