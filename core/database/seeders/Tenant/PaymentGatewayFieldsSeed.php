<?php

namespace Database\Seeders\Tenant;

use App\Models\PaymentGateway;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentGatewayFieldsSeed extends Seeder
{
    public static function execute()
    {
        $data = file_get_contents('assets/tenant/page-layout/payment-gateway.json');
        $all_data_decoded = json_decode($data);
        $package = tenant()->payment_log()->first()?->package()->first() ?? [];
        $all_features = $package->plan_features ?? [];
        $check_feature_name = $all_features->pluck('feature_name')->toArray();

        foreach ($all_data_decoded as $decoded){
            foreach ($decoded as $item){
                if(in_array($item->name,$check_feature_name)){
                    PaymentGateway::create([
                        'id' => $item->id,
                        'name' => $item->name,
                        'image' => $item->image,
                        'description' => $item->description,
                        'status' => $item->status,
                        'test_mode' => $item->test_mode,
                        'credentials' => $item->credentials,
                    ]);
                }
            }
        }
    }
}
