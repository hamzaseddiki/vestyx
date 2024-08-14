<?php

namespace Database\Seeders\Tenant\ModuleData\Service;

use App\Helpers\SeederHelpers\JsonDataModifier;
use Modules\Service\Entities\Service;

class ServiceSeed
{

    public static function execute()
    {
        $object = new JsonDataModifier('service','service');
        $data = $object->getColumnData([
            'title',
            'description',
            'category_id',
            'slug',
            'status',
            'image',
        ]);
        Service::insert($data);
    }
}
