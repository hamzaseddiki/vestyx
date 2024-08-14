<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDonationCategoriesTable extends Migration
{

    public function up()
    {
        Schema::create('donation_categories', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->nullable();
            $table->boolean('status')->default(0);
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('donation_categories');
    }
}
