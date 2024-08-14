<?php

namespace Database\Seeders\Tenant\ModuleData\Others;

use App\Helpers\SeederHelpers\JsonDataModifier;
use App\Models\Faq;

class FaqSeed
{

    public static function execute()
    {
        $object = new JsonDataModifier('faq','faq');
        $data = $object->getColumnData([
            'title',
            'description',
            'status',
            'category_id',
        ]);

        Faq::insert($data);
    }

}
