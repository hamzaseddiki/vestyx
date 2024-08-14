<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTestColumnManualAttachmentToProductsTable extends Migration
{

    public function up()
    {
       if(!Schema::hasColumn('product_orders','manual_payment_attachment')){
           Schema::table('product_orders', function (Blueprint $table) {
               $table->string('manual_payment_attachment')->nullable();
           });
       }
    }


    public function down()
    {
        if(!Schema::hasColumn('product_orders','manual_payment_attachment')) {
            Schema::table('product_orders', function (Blueprint $table) {
                $table->dropColumn('manual_payment_attachment');
            });
        }
    }
}
