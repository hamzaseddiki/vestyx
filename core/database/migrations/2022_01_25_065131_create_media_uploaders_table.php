<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaUploadersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_uploaders', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->text('path');
            $table->text('alt')->nullable();
            $table->string('size')->nullable();
            $table->unsignedBigInteger('user_type')->default(0);
            $table->unsignedBigInteger('user_id')->default(0);
            $table->string('dimensions')->nullable();
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
        Schema::dropIfExists('media_uploaders');
    }
}
