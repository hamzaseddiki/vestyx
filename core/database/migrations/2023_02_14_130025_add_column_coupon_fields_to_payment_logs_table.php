<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('payment_logs', function (Blueprint $table) {
            $table->unsignedBigInteger('coupon_id')->nullable()->after('tenant_id');
            $table->double('coupon_discount')->nullable()->after('coupon_id');
        });
    }

    public function down()
    {
        Schema::table('payment_logs', function (Blueprint $table) {
            $table->dropColumn('coupon_id');
            $table->dropColumn('coupon_discount');
        });
    }
};
