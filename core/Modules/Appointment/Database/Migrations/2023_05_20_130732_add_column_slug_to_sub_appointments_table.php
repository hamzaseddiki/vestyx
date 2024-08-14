<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnSlugToSubAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        if(!Schema::hasColumn('sub_appointments','slug')){
            Schema::table('sub_appointments', function (Blueprint $table) {
                $table->text('slug')->nullable()->after('title');
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
        if(!Schema::hasColumn('sub_appointments','slug')) {
            Schema::table('sub_appointments', function (Blueprint $table) {
                $table->dropColumn('slug');
            });
        }
    }
}
