<?php

namespace Database\Seeders\Tenant\ModuleData\Event;
use App\Helpers\SeederHelpers\JsonDataModifier;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Event\Entities\EventCategory;

class EventCategorySeed
{

    public static function execute()
    {

        if (!Schema::hasTable('event_categories')) {
            Schema::create('event_categories', function (Blueprint $table) {
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

        if (in_array('event', $check_feature_name)) {
            $object = new JsonDataModifier('event','event-category');
            $data = $object->getColumnData(['title','status']);
            EventCategory::insert($data);
        }
    }
}
