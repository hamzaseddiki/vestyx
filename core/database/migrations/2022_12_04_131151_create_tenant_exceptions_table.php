<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if(!Schema::hasTable('tenant_exceptions')){
            Schema::create('tenant_exceptions', function (Blueprint $table) {
                $table->id();
                $table->string('tenant_id');
                $table->string('issue_type')->nullable();
                $table->longText('description')->nullable();
                $table->boolean('domain_create_status')->default(1);
                $table->boolean('seen_status')->default(0);
                $table->timestamps();
            });
        }

    }

    public function down()
    {
        Schema::dropIfExists('tenant_exceptions');
    }
};
