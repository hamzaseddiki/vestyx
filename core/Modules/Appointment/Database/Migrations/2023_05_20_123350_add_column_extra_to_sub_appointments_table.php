<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnExtraToSubAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasColumns('sub_appointments',['views','is_popular'])){
            Schema::table('sub_appointments', function (Blueprint $table) {
                $table->bigInteger('views')->default(0);
                $table->string('is_popular')->nullable();
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
        if(!Schema::hasColumns('sub_appointments',['views','is_popular'])) {
            Schema::table('sub_appointments', function (Blueprint $table) {
                $table->dropColumn('views');
                $table->dropColumn('is_popular');
            });
        }
    }
}
