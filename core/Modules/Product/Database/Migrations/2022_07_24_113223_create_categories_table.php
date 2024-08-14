<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            if(!Schema::hasTable('categories')){ 
                Schema::create('categories', function (Blueprint $table) {
                    $table->id();
                    $table->string("name");
                    $table->string("slug")->unique();
                    $table->tinyText("description")->nullable();
                    $table->unsignedBigInteger("image_id")->nullable();
                    $table->timestamps();
                    $table->softDeletes();
                });
            }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
};
