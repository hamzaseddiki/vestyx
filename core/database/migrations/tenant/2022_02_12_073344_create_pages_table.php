<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->string('slug')->unique();
            $table->longText('page_content')->nullable();
            $table->unsignedBigInteger('visibility')->default(0);
            $table->unsignedBigInteger('breadcrumb')->default(0);
            $table->unsignedBigInteger('page_builder')->default(0);
            $table->unsignedBigInteger('status')->default(0);
            $table->string('navbar_variant')->nullable();
            $table->string('footer_variant')->nullable();
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
        Schema::dropIfExists('pages');
    }
}
