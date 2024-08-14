<?php

namespace Database\Seeders\Tenant\ModuleData\Donation;

use App\Helpers\SeederHelpers\JsonDataModifier;
use Modules\Donation\Entities\DonationActivity;

class DonationActivitySeed
{

    public static function execute()
    {
        $object = new JsonDataModifier('donation', 'donation-activities');
        $data = $object->getColumnData([
            'title',
            'description',
            'slug',
            'status',
            'image',
            'category_id',
        ]);

        DonationActivity::insert($data);

    }
}
