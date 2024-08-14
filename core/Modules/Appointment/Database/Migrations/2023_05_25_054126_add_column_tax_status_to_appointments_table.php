<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnTaxStatusToAppointmentsTable extends Migration
{

    public function up()
    {
        if(!Schema::hasColumn('appointments','tax_status')){
            Schema::table('appointments', function (Blueprint $table) {
                $table->string('tax_status')->nullable();
            });
        }

    }

    public function down()
    {
        if(!Schema::hasColumn('appointments','tax_status')){
            Schema::table('appointments', function (Blueprint $table) {
                $table->dropColumn('tax_status');
            });
        }
    }
}
