<?php

namespace Database\Seeders\Tenant;

use App\Helpers\ImageDataSeedingHelper;
use App\Helpers\SeederHelpers\JsonDataModifier;
use App\Mail\TenantCredentialMail;
use App\Models\Admin;
use App\Models\Language;
use App\Models\Menu;
use App\Models\Testimonial;
use App\Models\TopbarInfo;
use App\Models\WeddingPricePlan;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Mail;
use Modules\Event\Entities\Event;

class WeddingPricePlanSeed extends Seeder
{

    public static function excute()
    {
        $object = new JsonDataModifier('','price-plan');

        $data = $object->getColumnData([
            'title',
            'features',
            'not_available_features',
            'status',
            'price',
            'is_popular',
        ]);

        WeddingPricePlan::insert($data);

    }
}
