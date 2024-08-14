<?php

namespace Database\Seeders\Tenant\ModuleData\Others;

use App\Helpers\SeederHelpers\JsonDataModifier;
use App\Models\FaqCategory;

class FaqCategorySeed
{

    public static function execute()
    {
        $object = new JsonDataModifier('faq','faq-category');
        $data = $object->getColumnData(['title','status']);
        FaqCategory::insert($data);
    }

}
