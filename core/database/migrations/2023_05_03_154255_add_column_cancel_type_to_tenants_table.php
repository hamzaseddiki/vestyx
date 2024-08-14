<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
      if(!Schema::hasColumn('tenants','cancel_type')){
          Schema::table('tenants', function (Blueprint $table) {
              $table->string('cancel_type')->nullable();
          });
      }
    }


    public function down()
    {
        if(!Schema::hasColumn('tenants','cancel_type')) {
            Schema::table('tenants', function (Blueprint $table) {
                $table->dropColumn('cancel_type');
            });
        }
    }
};
