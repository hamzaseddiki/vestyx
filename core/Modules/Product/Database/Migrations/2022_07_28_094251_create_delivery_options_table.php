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
           if(!Schema::hasTable('delivery_options')){
                Schema::create('delivery_options', function (Blueprint $table) {
                    $table->id();
                    $table->string("icon");
                    $table->string("title");
                    $table->string("sub_title");
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
        Schema::dropIfExists('delivery_options');
    }
};
