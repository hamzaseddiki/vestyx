<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        if(!Schema::hasColumn('payment_logs','theme_code')){
            Schema::table('payment_logs', function (Blueprint $table) {
                $table->string('theme_code')->nullable()->after('theme');
            });
        }

    }

    public function down()
    {
        if(!Schema::hasColumn('payment_logs','theme_code')) {
            Schema::table('payment_logs', function (Blueprint $table) {
                $table->dropColumn('theme_code');
            });
        }
    }
};
