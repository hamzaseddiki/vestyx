<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        if(!Schema::hasColumn('users','image')){
            Schema::table('users', function (Blueprint $table) {
                $table->string('image')->nullable();
            });
        }

    }

    public function down()
    {
        if(!Schema::hasColumn('users','image')){
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('image');
            });
        }
    }
};
