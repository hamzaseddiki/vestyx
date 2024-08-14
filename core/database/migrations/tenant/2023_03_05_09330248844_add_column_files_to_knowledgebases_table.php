<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        if(!Schema::hasColumn('knowledgebases','files')){
            Schema::table('knowledgebases', function (Blueprint $table) {
                $table->longText('files')->nullable();
            });
        }

    }

    public function down()
    {
        if(!Schema::hasColumn('knowledgebases','files')){
            Schema::table('knowledgebases', function (Blueprint $table) {
                $table->dropColumn('files');
            });
        }
    }
};
