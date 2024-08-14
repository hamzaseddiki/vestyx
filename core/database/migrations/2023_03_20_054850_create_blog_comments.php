<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogComments extends Migration
{

    public function up()
    {
        if(!Schema::hasTable('blog_comments')){
            Schema::create('blog_comments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('blog_id');
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('parent_id')->nullable();
                $table->string('commented_by');
                $table->longText('comment_content');
                $table->timestamps();
            });
        }

    }

    public function down()
    {
        Schema::dropIfExists('blog_comments');
    }
}
