<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        if (!Schema::hasColumn('payment_logs','trial_expire_date')) {
            Schema::table('payment_logs', function (Blueprint $table) {
                $table->string('trial_expire_date')->nullable();
            });
        }
    }

    public function down()
    {
        Schema::table('payment_logs', function (Blueprint $table) {
            $table->dropColumn('trial_expire_date');
        });
    }
};
