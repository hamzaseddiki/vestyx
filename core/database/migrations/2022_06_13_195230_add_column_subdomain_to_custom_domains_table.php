<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnSubdomainToCustomDomainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('custom_domains', function (Blueprint $table) {
            $table->string('unique_key')->nullable();
        });
    }


    public function down()
    {
        Schema::table('custom_domains', function (Blueprint $table) {
            $table->dropColumn('unique_key');
        });
    }
}
