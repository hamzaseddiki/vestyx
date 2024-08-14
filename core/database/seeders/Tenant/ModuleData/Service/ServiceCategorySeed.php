<?php

namespace Database\Seeders\Tenant\ModuleData\Service;

use App\Helpers\SeederHelpers\JsonDataModifier;
use Illuminate\Support\Facades\DB;
use Modules\Event\Entities\EventCategory;
use Modules\Job\Entities\JobCategory;
use Modules\Service\Entities\Service;
use Modules\Service\Entities\ServiceCategory;

class ServiceCategorySeed
{

    public static function execute()
    {
        $object = new JsonDataModifier('service','service-category');
        $data = $object->getColumnData(['title','status']);
        ServiceCategory::insert($data);
    }
}
