<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnCategoryIdsToAppointmentsTable extends Migration
{

    public function up()
    {

        if(!Schema::hasColumns('appointments',['appointment_category_id','appointment_subcategory_id'])){
            Schema::table('appointments', function (Blueprint $table) {
                $table->unsignedBigInteger('appointment_category_id')->after('id');
                $table->unsignedBigInteger('appointment_subcategory_id')->after('appointment_category_id');
            });
        }

    }

    public function down()
    {
        if(!Schema::hasColumns('appointments',['appointment_category_id','appointment_subcategory_id'])) {
            Schema::table('appointments', function (Blueprint $table) {
                $table->dropColumn('appointment_category_id');
                $table->dropColumn('appointment_category_id');
            });
        }
    }
}
