<?php

namespace Database\Seeders\Tenant\ModuleData\Knowledgebase;

use App\Helpers\SeederHelpers\JsonDataModifier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Modules\Job\Entities\JobCategory;
use Modules\Knowledgebase\Entities\KnowledgebaseCategory;

class KnowledgebaseCategorySeed
{

    public static function execute()
    {

        if (!Schema::hasTable('knowledgebase_categories')) {
            Schema::create('knowledgebase_categories', function (Blueprint $table) {
                $table->id();
                $table->text('title');
                $table->string('image');
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

        if (in_array('knowledgebase', $check_feature_name)) {
            $object = new JsonDataModifier('article','article-category');
            $data = $object->getColumnData(['title','image','status']);
            KnowledgebaseCategory::insert($data);
        }
    }
}
