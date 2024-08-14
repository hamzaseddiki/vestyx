<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('cronjob_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cronjob_id')->nullable();
            $table->text('title')->nullable();
            $table->string('type')->nullable();
            $table->string('running_qty')->nullable();
            $table->text('others')->nullable();
            $table->boolean('status')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cronjob_logs');
    }
};
