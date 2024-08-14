<?php

namespace Database\Seeders\Tenant\ModuleData\Others;

use App\Helpers\ImageDataSeedingHelper;
use App\Helpers\SanitizeInput;
use App\Helpers\SeederHelpers\JsonDataModifier;
use App\Models\Brand;
use App\Models\Testimonial;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Service\Entities\Service;

class TestimonialSeed extends Seeder
{

    public static function execute()
    {
        $object = new JsonDataModifier('','testimonial');
        $data = $object->getColumnData([
            'name',
            'designation',
            'company',
            'description',
            'status',
            'image',
        ]);

        Testimonial::insert($data);
    }
}
