<?php

namespace Database\Seeders\Tenant\ModuleData\Portfolio;

use App\Helpers\SeederHelpers\JsonDataModifier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Modules\Job\Entities\JobCategory;
use Modules\Portfolio\Entities\PortfolioCategory;

class PortfolioCategorySeed
{

    public static function execute()
    {

        if (!Schema::hasTable('portfolio_categories')) {
            Schema::create('portfolio_categories', function (Blueprint $table) {
                $table->id();
                $table->string('title');
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

        if (in_array('portfolio', $check_feature_name)) {

            $object = new JsonDataModifier('portfolio','portfolio-category');
            $data = $object->getColumnData(['title','status']);
            PortfolioCategory::insert($data);
        }
    }

}
