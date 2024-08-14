<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTopbarInfosTable extends Migration
{

    public function up()
    {
        Schema::create('topbar_infos', function (Blueprint $table) {
            $table->id();
            $table->string('icon')->nullable();
            $table->text('url')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('topbar_infos');
    }
}
