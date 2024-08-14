<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonationCommentsTable extends Migration
{

    public function up()
    {
        Schema::create('donation_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('donation_id');
            $table->unsignedBigInteger('user_id');
            $table->string('commented_by');
            $table->longText('comment_content');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('donation_comments');
    }
}
