<?php

namespace Database\Seeders\Tenant\ModuleData\Event;
use App\Helpers\SeederHelpers\JsonDataModifier;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Event\Entities\Event;

class EventSeed
{

    public static function execute()
    {

        if (!Schema::hasTable('events')) {

            Schema::create('events', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('category_id');
                $table->text('title');
                $table->text('slug')->nullable();
                $table->longText('content');
                $table->string('date');
                $table->string('time');
                $table->bigInteger('cost');
                $table->bigInteger('total_ticket');
                $table->bigInteger('available_ticket')->nullable();
                $table->string('image')->nullable();
                $table->string('organizer')->nullable();
                $table->string('organizer_email')->nullable();
                $table->string('organizer_phone')->nullable();
                $table->text('venue_location')->nullable();
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

            $object = new JsonDataModifier('event','event');

            $data = $object->getColumnData([
                'title',
                'content',
                'category_id',
                'slug',
                'date',
                'time',
                'status',
                'image',
                'cost',
                'total_ticket',
                'available_ticket',
                'organizer',
                'organizer_email',
                'organizer_phone',
                'venue_location',
            ]);

            Event::insert($data);
        }
    }
}
