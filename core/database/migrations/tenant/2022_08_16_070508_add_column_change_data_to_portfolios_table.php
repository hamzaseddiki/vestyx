<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('portfolios', function (Blueprint $table) {
          // $table->text('title')->change();
//            $table->text('client')->change();
//            $table->text('design')->change();
//            $table->text('typography')->change();
        });
    }
};
