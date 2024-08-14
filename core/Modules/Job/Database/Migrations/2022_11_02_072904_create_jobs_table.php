<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobsTable extends Migration
{

    public function up()
    {
        if(!Schema::hasTable('jobs')){     
            Schema::create('jobs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('category_id');
                $table->text('title');
                $table->text('slug')->nullable();
                $table->longText('description');
                $table->text('experience');
                $table->text('designation');
                $table->string('employee_type');
                $table->string('working_days');
                $table->string('working_type');
                $table->text('job_location');
                $table->text('company_name');
                $table->double('salary_offer');
                $table->string('image')->nullable();
                $table->string('deadline');
                $table->string('application_fee_status')->nullable();
                $table->bigInteger('application_fee')->nullable();
                $table->boolean('status')->default(1);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('jobs');
    }
}
