<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobCategoriesTable extends Migration
{

    public function up()
    {
     if(!Schema::hasTable('job_categories')){     
            Schema::create('job_categories', function (Blueprint $table) {
                $table->id();
                $table->text('title');
                $table->text('subtitle')->nullable();
                $table->string('image')->nullable();
                $table->boolean('status')->default(1);
                $table->timestamps();
            });
      }
    }

    public function down()
    {
        Schema::dropIfExists('job_categories');
    }
}
