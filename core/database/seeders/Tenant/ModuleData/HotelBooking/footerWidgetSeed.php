<?php

namespace Database\Seeders\Tenant\ModuleData\HotelBooking;
use Illuminate\Database\Seeder;


class footerWidgetSeed extends Seeder
{
    public static function run()
    {
        $only_path = 'assets/tenant/page-layout/widgets.json';

        if (file_exists($only_path)) {
            $all_widgets = file_get_contents($only_path);
            $all_data_decoded = json_decode($all_widgets);

            $isHotelBookingExists = collect($all_data_decoded->data)->pluck('widget_location')->contains("hotel_booking_footer");

            if (!$isHotelBookingExists) {
                $hotel_booking_widget_path = 'core/Modules/HotelBooking/assets/page-layout/hotel-booking-widgets.json';
                $hotel_booking_widget_content = file_get_contents($hotel_booking_widget_path);

                $additional_content = json_decode($hotel_booking_widget_content);
                if ($additional_content) {
                    // Merge data arrays from both sources
                    $merged_data = array_merge($all_data_decoded->data, $additional_content);

                    // Convert serialized widget_content to JSON format
                    foreach ($merged_data as $item) {
                        if (isset($item->widget_content)) {

                            $isSerialized = false;

                            if (is_string($item->widget_content)) {
                                $data = @unserialize($item->widget_content);
                                if ($data !== false || $item->widget_content === 'b:0;') {
                                    $isSerialized = true;
                                }
                            }
                            if ($isSerialized) {
                                $unserialized = unserialize($item->widget_content);
                                if ($unserialized !== false) {
                                    $item->widget_content = json_encode($unserialized);
                                }
                            }

                        }
                    }

                    // Create a new object to hold the merged data
                    $merged_object = new \stdClass();
                    $merged_object->data = $merged_data;

                    // Encode the merged data back to JSON
                    $merged_json = json_encode($merged_object);

                    // Write the merged JSON content to the original file
                    file_put_contents($only_path, $merged_json);
                }
            }
        }
    }
}
