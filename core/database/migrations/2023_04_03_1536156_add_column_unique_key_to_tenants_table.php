<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('tenants', 'unique_key')) {
            Schema::table('tenants', function (Blueprint $table) {
                $table->text('unique_key')->nullable();
            });
        }

    }

    public function down()
    {
        if (!Schema::hasColumn('tenants', 'unique_key')) {
            Schema::table('tenants', function (Blueprint $table) {
                $table->dropColumn('unique_key');
            });
        }
    }
};
