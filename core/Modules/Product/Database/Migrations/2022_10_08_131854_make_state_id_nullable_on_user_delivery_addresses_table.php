<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_delivery_addresses', function (Blueprint $table) {
            $table->unsignedBigInteger('state_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_delivery_addresses', function (Blueprint $table) {
            $table->unsignedBigInteger('state_id')->nullable(false)->change();
        });
    }
};
