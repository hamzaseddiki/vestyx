<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePortfoliosTable extends Migration
{

    public function up()
    {
          if(!Schema::hasTable('portfolios')){
                Schema::create('portfolios', function (Blueprint $table) {
                    $table->id();
                    $table->bigInteger('category_id');
                    $table->text('title');
                    $table->text('url');
                    $table->longText('description');
                    $table->text('slug');
                    $table->string('image');
                    $table->string('image_gallery')->nullable();
                    $table->text('client')->nullable();
                    $table->text('design')->nullable();
                    $table->text('typography')->nullable();
                    $table->text('tags')->nullable();
                    $table->text('file')->nullable();
                    $table->text('download')->nullable();
                    $table->boolean('status')->default(1);
                    $table->timestamps();
                });
          }
    }

    public function down()
    {
        Schema::dropIfExists('portfolios');
    }
}
