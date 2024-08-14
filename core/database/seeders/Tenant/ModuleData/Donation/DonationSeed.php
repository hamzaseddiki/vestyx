<?php

namespace Database\Seeders\Tenant\ModuleData\Donation;

use App\Helpers\SeederHelpers\JsonDataModifier;
use Modules\Donation\Entities\Donation;

class DonationSeed
{

    public static function execute()
    {
        $object = new JsonDataModifier('donation','donation');
        $data = $object->getColumnData([
            'title',
            'description',
            'slug',
            'status',
            'amount',
            'image',
            'creator_id',
            'created_by',
            'category_id',
            'deadline',
            'popular',
            'views',
            'faq',
        ]);

        Donation::insert($data);
    }

}
