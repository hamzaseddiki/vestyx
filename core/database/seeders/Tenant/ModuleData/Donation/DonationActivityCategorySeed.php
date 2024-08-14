<?php

namespace Database\Seeders\Tenant\ModuleData\Donation;

use App\Helpers\SeederHelpers\JsonDataModifier;
use Modules\Donation\Entities\DonationActivityCategory;

class DonationActivityCategorySeed
{

    public static function execute()
    {
        $object = new JsonDataModifier('donation','donation-activities-category');
        $data = $object->getColumnData(['title','status','slug']);
        DonationActivityCategory::insert($data);
    }
}
