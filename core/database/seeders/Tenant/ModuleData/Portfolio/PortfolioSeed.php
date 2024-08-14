<?php

namespace Database\Seeders\Tenant\ModuleData\Portfolio;

use App\Helpers\SeederHelpers\JsonDataModifier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Modules\Job\Entities\Job;
use Modules\Portfolio\Entities\Portfolio;
use Modules\Portfolio\Entities\PortfolioCategory;

class PortfolioSeed
{
    public static function execute()
    {

        if (!Schema::hasTable('portfolios')) {
            Schema::create('portfolios', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('category_id');
                $table->text('title');
                $table->text('url');
                $table->longText('description');
                $table->text('slug');
                $table->string('image');
                $table->string('image_gallery')->nullable();
                $table->text('client')->nullable();
                $table->text('design')->nullable();
                $table->text('typography')->nullable();
                $table->text('tags')->nullable();
                $table->text('file')->nullable();
                $table->text('download')->nullable();
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

            $object = new JsonDataModifier('portfolio','portfolio');
            $data = $object->getColumnData([
                'title',
                'description',
                'client',
                'design',
                'typography',
                'category_id',
                'slug',
                'status',
                'image',
                'image_gallery',
                'url',
                'tags',
                'file',
                'download',
            ]);

            Portfolio::insert($data);

        }
    }

}
