<?php

namespace Database\Seeders\Tenant\ModuleData\HotelBooking;
use Illuminate\Database\Seeder;


class CurrentHomeSeed extends Seeder
{
    public static function run()
    {
        $only_path = 'assets/tenant/page-layout/dynamic-pages.json';

        if (file_exists($only_path) && !is_dir($only_path)) {

            $dynamic_pages = file_get_contents($only_path);
            $all_data_decoded = json_decode($dynamic_pages);

            $isHotelBookingExists = collect($all_data_decoded->data)->pluck('slug')->contains("home-page-hotel-booking");

            if($isHotelBookingExists == false)
            {
                $curren_home_path = 'core/Modules/HotelBooking/assets/page-layout/hotel-booking-current-home.json';
                $current_home_page = file_get_contents($curren_home_path);
                $additional_content = json_decode($current_home_page);

                if ($additional_content) {
                    // Merge additional content with existing data
                    $all_data_decoded->data[] = $additional_content;

                    // Encode all data back to JSON
                    $updated_json = json_encode($all_data_decoded);

                    // Write updated JSON content to the original file
                    file_put_contents($only_path, $updated_json);
                }
            }

        }
    }
}
