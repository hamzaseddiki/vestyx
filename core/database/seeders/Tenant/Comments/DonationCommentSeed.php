<?php

namespace Database\Seeders\Tenant\Comments;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class DonationCommentSeed
{

    public static function execute()
    {
        if(!Schema::hasTable('donation_comments')){
            Schema::create('donation_comments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('donation_id');
                $table->unsignedBigInteger('user_id');
                $table->string('commented_by');
                $table->longText('comment_content');
                $table->timestamps();
            });
        }
    }

}
