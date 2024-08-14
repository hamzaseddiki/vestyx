<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKnowledgebaseCategoriesTable extends Migration
{

    public function up()
    {
       if(!Schema::hasTable('knowledgebase_categories')){  
            Schema::create('knowledgebase_categories', function (Blueprint $table) {
                $table->id();
                $table->text('title');
                $table->string('image');
                $table->boolean('status')->default(1);
                $table->timestamps();
            });
       }
    }

    public function down()
    {
        Schema::dropIfExists('knowledgebase_categories');
    }
}
