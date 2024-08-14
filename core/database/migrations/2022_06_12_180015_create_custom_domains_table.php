<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomDomainsTable extends Migration
{

    public function up()
    {
        Schema::create('custom_domains', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('custom_domain')->nullable();
            $table->string('custom_domain_status')->nullable();
            $table->string('old_domain')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('custom_domains');
    }
}
