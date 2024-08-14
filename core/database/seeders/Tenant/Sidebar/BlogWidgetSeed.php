<?php

namespace Database\Seeders\Tenant\Sidebar;

use App\Helpers\ImageDataSeedingHelper;
use App\Models\PageBuilder;
use App\Models\Widgets;
use Illuminate\Database\Seeder;

class BlogWidgetSeed extends Seeder
{
    public function run()
    {
        //blog search
        Widgets::create([
            'widget_name' => 'BlogSearchWidget',
            'widget_order' => '1',
            'widget_location' => 'sidebar',
            'widget_content' => serialize($this->blog_search_content()),
        ]);

        //blog search
        Widgets::create([
            'widget_name' => 'BlogCategoryWidget',
            'widget_order' => '2',
            'widget_location' => 'sidebar',
            'widget_content' => serialize($this->blog_category_content()),
        ]);

        //recent blog
        Widgets::create([
            'widget_name' => 'RecentBlogPostWidget',
            'widget_order' => '2',
            'widget_location' => 'sidebar',
            'widget_content' => serialize($this->recent_blog_content()),
        ]);

    }

    private function blog_search_content(){

        $data =  array (
            'id' => '1',
            'widget_name' => 'BlogSearchWidget',
            'widget_type' => 'update',
            'widget_location' => 'sidebar',
            'widget_order' => '1',
            'widget_title_en_US' => NULL,
            'widget_title_ar' => NULL,
        );

        return $data;

    }

    private function blog_category_content(){

        $data =

            array (
                'widget_name' => 'BlogCategoryWidget',
                'widget_type' => 'new',
                'widget_location' => 'sidebar',
                'widget_order' => '2',
                'widget_title_en_US' => 'Category',
                'widget_title_ar' => 'فئة',
                'category_items' => '4',
            );

        return $data;

    }

    private function recent_blog_content(){

        $data =

            array (
                'widget_name' => 'RecentBlogPostWidget',
                'widget_type' => 'new',
                'widget_location' => 'sidebar',
                'widget_order' => '3',
                'heading_text_en_US' => 'Most Visited Blogs',
                'heading_text_ar' => 'المدونات الأكثر زيارة',
                'blog_items' => '3',
            );

        return $data;

    }



}
