<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{

    public function up()
    {
         if(!Schema::hasTable('events')){
            Schema::create('events', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('category_id');
                $table->text('title');
                $table->text('slug')->nullable();
                $table->longText('content');
                $table->string('date');
                $table->string('time');
                $table->bigInteger('cost');
                $table->bigInteger('total_ticket');
                $table->bigInteger('available_ticket')->nullable();
                $table->string('image')->nullable();
                $table->string('organizer')->nullable();
                $table->string('organizer_email')->nullable();
                $table->string('organizer_phone')->nullable();
                $table->text('venue_location')->nullable();
                $table->boolean('status')->default(1);
                $table->timestamps();
            });
         }
    }

    public function down()
    {
        Schema::dropIfExists('events');
    }
}
