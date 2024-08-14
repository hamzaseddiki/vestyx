<?php

namespace Database\Seeders\Tenant\ModuleData\Blog;

use App\Helpers\ImageDataSeedingHelper;
use App\Helpers\SanitizeInput;
use App\Helpers\SeederHelpers\JsonDataModifier;
use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Blog\Entities\Blog;
use Modules\Blog\Entities\BlogCategory;
use Modules\Event\Entities\Event;

class BlogSeed extends Seeder
{

    public static function execute()
    {
        $object = new JsonDataModifier('blog','blog');
        $data = $object->getColumnData([
            'title',
            'blog_content',
            'category_id',
            'admin_id',
            'slug',
            'status',
            'image',
            'author',
            'excerpt',
            'tags',
            'image_gallery',
            'views',
            'video_url',
            'featured',
            'created_by',
        ]);

        Blog::insert($data);

    }



}
