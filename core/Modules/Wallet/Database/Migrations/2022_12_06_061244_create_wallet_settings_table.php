<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWalletSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_settings', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->boolean('renew_package')->nullable()->default(0);
            $table->boolean('wallet_alert')->nullable()->default(0);
            $table->double('minimum_amount')->nullable();

            $table->index(['user_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('wallet_settings');
    }
}
