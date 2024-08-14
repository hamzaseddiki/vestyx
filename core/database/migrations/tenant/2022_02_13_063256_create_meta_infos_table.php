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
            $table->text('title')->nullable();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            /* facebook */
            $table->text('fb_title')->nullable();
            $table->text('fb_description')->nullable();
            $table->string('fb_image')->nullable();
            /* twitter */
            $table->text('tw_title')->nullable();
            $table->text('tw_description')->nullable();
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
