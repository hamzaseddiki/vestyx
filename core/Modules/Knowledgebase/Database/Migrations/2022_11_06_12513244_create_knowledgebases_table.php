<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKnowledgebasesTable extends Migration
{
    public function up()
    {
        if(!Schema::hasTable('knowledgebases')){    
            Schema::create('knowledgebases', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('category_id');
                $table->text('title');
                $table->text('slug');
                $table->longText('description');
                $table->string('image');
                $table->bigInteger('views')->default(0);
                $table->boolean('status')->default(1);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('knowledgebases');
    }
}
