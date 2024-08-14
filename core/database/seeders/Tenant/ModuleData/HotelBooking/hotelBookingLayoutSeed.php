<?php

namespace Database\Seeders\Tenant\ModuleData\HotelBooking;
use Illuminate\Database\Seeder;


class hotelBookingLayoutSeed extends Seeder
{
    public static function run()
    {
        $only_path = 'assets/tenant/page-layout/home-pages/hotel-booking-layout.json';

        if (!file_exists($only_path) && !is_dir($only_path)) {
            $hotel_booking_layout_path = 'core/Modules/HotelBooking/assets/page-layout/home-page/hotel-booking-layout.json';

            $hotel_booking_layout_content = file_get_contents($hotel_booking_layout_path);
            file_put_contents($only_path, $hotel_booking_layout_content);
        }
    }
}
