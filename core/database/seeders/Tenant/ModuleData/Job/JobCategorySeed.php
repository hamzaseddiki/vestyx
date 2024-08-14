<?php

namespace Database\Seeders\Tenant\ModuleData\Job;

use App\Helpers\SeederHelpers\JsonDataModifier;
use App\Models\FaqCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Modules\Event\Entities\EventCategory;
use Modules\Job\Entities\JobCategory;

class JobCategorySeed
{

    public static function execute()
    {

        if (!Schema::hasTable('job_categories')) {

            Schema::create('job_categories', function (Blueprint $table) {
                $table->id();
                $table->text('title');
                $table->text('subtitle')->nullable();
                $table->string('image')->nullable();
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
            $object = new JsonDataModifier('job','job-category');
            $data = $object->getColumnData(['title','subtitle','image','status']);
            JobCategory::insert($data);
        }
    }
}
