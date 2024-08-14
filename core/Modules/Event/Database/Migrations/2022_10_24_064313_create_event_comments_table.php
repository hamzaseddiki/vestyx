<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventCommentsTable extends Migration
{

    public function up()
    {
          if(!Schema::hasTable('event_comments')){    
        
            Schema::create('event_comments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('event_id');
                $table->unsignedBigInteger('user_id');
                $table->string('commented_by');
                $table->longText('comment_content');
                $table->timestamps();
            });
          }
    }

    public function down()
    {
        Schema::dropIfExists('event_comments');
    }
}
