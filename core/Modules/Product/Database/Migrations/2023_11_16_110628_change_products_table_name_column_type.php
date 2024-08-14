<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ChangeProductsTableNameColumnType extends Migration
{
    public function up()
    {
        // Drop the existing index
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_name_index');
        });

        // Change column type to longText
        DB::statement('ALTER TABLE products MODIFY name TEXT');

        // Re-create the index after modifying the column with a specified key length
        DB::statement('CREATE INDEX products_name_index ON products (name(191))');
        // Change the key length (191 in this case) based on your needs and MySQL configuration
    }

    public function down()
    {
        // Drop the index
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('products_name_index');
        });

        // Change column type back to string (or your original type)
        DB::statement('ALTER TABLE products MODIFY name VARCHAR(255)');

        // Re-create the index after reverting the column type
        Schema::table('products', function (Blueprint $table) {
            $table->index('name', 'products_name_index');
        });
    }
}
