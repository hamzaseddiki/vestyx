<?php

namespace Database\Seeders\Tenant\ModuleData\Job;

use App\Helpers\SeederHelpers\JsonDataModifier;
use App\Models\Faq;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Modules\Event\Entities\Event;
use Modules\Job\Entities\Job;

class JobSeed
{


    public static function execute()
    {
        if (!Schema::hasTable('jobs')) {
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


        $package = tenant()->user()->first()?->payment_log()->first()?->package()->first() ?? [];
        $all_features = $package->plan_features ?? [];

        $payment_log = tenant()->user()->first()?->payment_log()?->first() ?? [];
        if(empty($all_features) && $payment_log->status != 'trial'){
            return;
        }

        $check_feature_name = $all_features->pluck('feature_name')->toArray();

        if (in_array('job', $check_feature_name)) {

            $object = new JsonDataModifier('job','job');
            $data = $object->getColumnData([
                'title',
                'description',
                'designation',
                'job_location',
                'company_name',
                'category_id',
                'slug',
                'status',
                'image',
                'experience',
                'employee_type',
                'working_days',
                'working_type',
                'salary_offer',
                'deadline',
                'application_fee_status',
                'application_fee',
            ]);

            Job::insert($data);

        }
    }
}
