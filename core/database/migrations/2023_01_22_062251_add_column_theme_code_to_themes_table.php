<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
      if(!Schema::hasColumn('themes','theme_code')){
          Schema::table('themes', function (Blueprint $table) {
              $table->string('theme_code')->nullable();
          });
      }
    }

    public function down()
    {
        Schema::table('themes', function (Blueprint $table) {
              $table->dropColumn('theme_code');
        });
    }
};
