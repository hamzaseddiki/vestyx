<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMetaInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meta_infos', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->string('image')->nullable();
            /* facebook */
            $table->string('fb_title')->nullable();
            $table->string('fb_description')->nullable();
            $table->string('fb_image')->nullable();
            /* twitter */
            $table->string('tw_title')->nullable();
            $table->string('tw_description')->nullable();
            $table->string('tw_image')->nullable();
            $table->unsignedBigInteger('metainfoable_id');
            $table->string('metainfoable_type');

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
        Schema::dropIfExists('meta_infos');
    }
}
