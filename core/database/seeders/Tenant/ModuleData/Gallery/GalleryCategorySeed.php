<?php

namespace Database\Seeders\Tenant\ModuleData\Gallery;
use App\Helpers\SeederHelpers\JsonDataModifier;
use App\Models\ImageGallery;
use App\Models\ImageGalleryCategory;
use App\Models\Testimonial;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Service\Entities\ServiceCategory;

class GalleryCategorySeed
{

    public static function execute()
    {

        if(!Schema::hasTable('image_gallery_categories')){
            Schema::create('image_gallery_categories', function (Blueprint $table) {
                $table->id();
                $table->string('title');
                $table->boolean('status')->default(1);
                $table->timestamps();
            });
        }

        $object = new JsonDataModifier('gallery','gallery-category');
        $data = $object->getColumnData([
            'title',
            'status',
        ]);

        ImageGalleryCategory::insert($data);

    }
}
