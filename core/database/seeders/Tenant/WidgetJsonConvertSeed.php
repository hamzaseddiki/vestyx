<?php

namespace Database\Seeders\Tenant;


use App\Models\Widgets;
use Illuminate\Database\Seeder;

class WidgetJsonConvertSeed extends Seeder
{
    public static function run()
    {
        $widgets = Widgets::all();
        foreach ($widgets as $item)
        {
            if (isset($item->widget_content) && is_string($item->widget_content)) {
                // Attempt to unserialize
                $unserializedData = @unserialize($item->widget_content);

                // Check if unserialization was successful
                if ($unserializedData !== false || $item->widget_content === 'b:0;') {
                    $unserialized = unserialize($item->widget_content);
                    if ($unserialized !== false) {
                        $item->widget_content = json_encode($unserialized);
                        $item->save();
                    }
                }
                elseif (json_decode($item->widget_content) !== null)
                {
                    continue;
                } else
                {
                    $item->delete();
                }
            }
        }
    }
}

