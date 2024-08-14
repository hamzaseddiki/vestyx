<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDonationUpdatesTable extends Migration
{
    public function up()
    {
        Schema::create('donation_updates', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('donation_id')->unsigned();
            $table->text('title')->nullable();
            $table->longText('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('status')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('donation_updates');
    }
}
