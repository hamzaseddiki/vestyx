<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnSlugToAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumn('appointments','slug')) {
            Schema::table('appointments', function (Blueprint $table) {
                $table->text('slug')->after('title')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if(!Schema::hasColumn('appointments','slug')) {
            Schema::table('appointments', function (Blueprint $table) {
                $table->dropColumn('slug');
            });
        }
    }
}
