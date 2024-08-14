<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanFeaturesTable extends Migration
{

    public function up()
    {
        Schema::create('plan_features', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('plan_id');
            $table->string('feature_name');
            $table->boolean('status')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('plan_features');
    }
}
