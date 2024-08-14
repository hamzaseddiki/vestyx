<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::table('payment_logs', function (Blueprint $table) {
            $table->string('manual_payment_attachment')->nullable();
        });
    }

    public function down()
    {
        Schema::table('payment_logs', function (Blueprint $table) {
            $table->dropColumn('manual_payment_attachment');
        });
    }
};
