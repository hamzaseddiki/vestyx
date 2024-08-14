<?php

namespace Database\Seeders\Tenant\Comments;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class EventCommentSeed
{

    public static function execute()
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

}
