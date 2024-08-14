<?php

namespace Database\Seeders\Tenant;

use App\Models\PaymentGateway;
use App\Models\StaticOption;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TenantDemoDataSeed extends Seeder
{
    public static function execute()
    {

        $only_path = 'assets/tenant/page-layout/tenant-demo-data.json';

        if (!file_exists($only_path) && !is_dir($only_path)) {
            $data = [
                "site_es_CO_title" => "site title",
                "site_es_CO_footer_copyright_text" => null,
                "site_en_US_title" => "site title",
                "site_en_US_footer_copyright_text" => null,
                "site_ar_title" => "\u0627\u0644\u0639\u0646\u0648\u0627\u0646 \u0627\u0644\u0645\u0648\u0642\u0639\u064a",
                "site_ar_footer_copyright_text" => "\u0627\u0644\u0639\u0646\u0648\u0627\u0646 \u0627\u0644\u0645\u0648\u0642\u0639\u064a",
                "site_bn_BD_title" => null,
                "site_bn_BD_footer_copyright_text" => null,
                "site_third_party_tracking_code_just_after_head" => null,
                "site_third_party_tracking_code" => null,
                "site_third_party_tracking_code_just_after_body" => null,
                "site_third_party_tracking_code_just_before_body_close" => null,
                "site_google_analytics" => null,
                "site_google_captcha_v3_site_key" => null,
                "site_google_captcha_v3_secret_key" => null,
                "social_facebook_status" => "on",
                "facebook_client_id" => null,
                "facebook_client_secret" => null,
                "social_google_status" => "on",
                "google_client_id" => null,
                "google_client_secret" => "D5J14HZQjxefNuKKRuHPfF42",
                "google_adsense_publisher_id" => null,
                "google_adsense_customer_id" => null
            ];

            $json_data = json_encode($data);
            file_put_contents($only_path, $json_data);
        }

        $all_data  = file_get_contents($only_path);
        $all_data_decoded = json_decode($all_data);

        foreach ($all_data_decoded as $key=>$item)
        {
            StaticOption::updateOrCreate([
                'option_name' => $key,
                'option_value' => $item,
            ]);
        }
    }
}
