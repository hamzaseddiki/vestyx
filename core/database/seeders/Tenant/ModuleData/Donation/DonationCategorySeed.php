<?php

namespace Database\Seeders\Tenant\ModuleData\Donation;

use App\Helpers\SeederHelpers\JsonDataModifier;
use Modules\Donation\Entities\DonationCategory;

class DonationCategorySeed
{
    public static function execute()
    {
        $object = new JsonDataModifier('donation','donation-category');
        $data = $object->getColumnData(['title','status','slug']);
        DonationCategory::insert($data);
    }

}
