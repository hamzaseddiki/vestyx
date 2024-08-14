<?php

namespace Database\Seeders\Tenant\ModuleData\Gallery;
use App\Helpers\SeederHelpers\JsonDataModifier;
use App\Models\ImageGallery;
use App\Models\ImageGalleryCategory;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Modules\Service\Entities\Service;

class GallerySeed
{

    public static function execute()
    {
        if(!Schema::hasTable('image_galleries')){
            Schema::create('image_galleries', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('category_id');
                $table->string('title');
                $table->text('subtitle')->nullable();
                $table->string('image');
                $table->boolean('status')->default(1);
                $table->timestamps();
            });
        }

        $object = new JsonDataModifier('gallery','gallery');
        $data = $object->getColumnData([
            'title',
            'subtitle',
            'status',
            'image',
            'category_id',
        ]);

        ImageGallery::insert($data);
    }
}
