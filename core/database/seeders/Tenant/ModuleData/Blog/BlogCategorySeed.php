<?php

namespace Database\Seeders\Tenant\ModuleData\Blog;

use App\Helpers\SeederHelpers\JsonDataModifier;
use Modules\Blog\Entities\BlogCategory;

class BlogCategorySeed
{

    public static function run()
    {
        $object = new JsonDataModifier('blog','blog-category');
        $data = $object->getColumnData(['title','status']);
        BlogCategory::insert($data);
    }



}
