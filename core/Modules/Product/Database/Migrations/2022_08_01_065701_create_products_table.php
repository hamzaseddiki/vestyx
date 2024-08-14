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
        if(!Schema::hasTable('products')){
            Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->string('slug')->index();
            $table->longText('summary')->nullable();
            $table->longText('description')->nullable();
            $table->string('image_id')->nullable();
            $table->double('price')->nullable();
            $table->double('sale_price')->nullable();
            $table->double('cost')->nullable();
            $table->unsignedBigInteger('badge_id')->index()->nullable();
            $table->unsignedBigInteger('brand_id')->index()->nullable();
            $table->unsignedBigInteger('status_id')->default('2');
            $table->unsignedBigInteger('product_type')->index()->default(1);
            $table->integer('sold_count')->nullable();
            $table->integer('min_purchase')->nullable();
            $table->integer('max_purchase')->nullable();
            $table->boolean('is_refundable')->index()->nullable();
            $table->boolean('is_in_house')->index()->default(1)->comment("Not one means vendor created product");
            $table->boolean('is_inventory_warn_able')->index()->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
        }


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
