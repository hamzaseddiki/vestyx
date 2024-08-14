<?php

namespace Database\Seeders\Tenant\ModuleData;

use App\Helpers\SeederHelpers\JsonDataModifier;
use App\Models\Testimonial;
use App\Models\Widgets;
use Illuminate\Support\Facades\DB;

class WidgetSeed
{
    public static function execute()
    {
        $object = new JsonDataModifier('','widgets');
        $data = $object->getColumnData([
            'widget_area',
            'widget_order',
            'widget_location',
            'widget_name',
            'widget_content',
        ]);

        Widgets::insert($data);
    }


}
