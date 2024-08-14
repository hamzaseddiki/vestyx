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
            if(!Schema::hasTable('child_categories')){
        
                Schema::create('child_categories', function (Blueprint $table) {
                    $table->id();
                    $table->unsignedBigInteger("category_id");
                    $table->unsignedBigInteger("sub_category_id");
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
        Schema::dropIfExists('child_categories');
    }
};
