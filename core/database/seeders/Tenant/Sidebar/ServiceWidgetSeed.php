<?php

namespace Database\Seeders\Tenant\Sidebar;

use App\Helpers\ImageDataSeedingHelper;
use App\Models\PageBuilder;
use App\Models\Widgets;
use Illuminate\Database\Seeder;

class ServiceWidgetSeed extends Seeder
{
    public function run()
    {
        //service category
        Widgets::create([
            'widget_name' => 'TenantServiceCategoryWidget',
            'widget_order' => '1',
            'widget_location' => 'service',
            'widget_content' => serialize($this->service_category_content()),
        ]);

        //service custom form
        Widgets::create([
            'widget_name' => 'TenantCustomFormWidget',
            'widget_order' => '2',
            'widget_location' => 'service',
            'widget_content' => serialize($this->service_custom_form()),
        ]);

    }

    private function service_category_content(){

       $data =  array (
            'widget_name' => 'TenantServiceCategoryWidget',
            'widget_type' => 'new',
            'widget_location' => 'service',
            'widget_order' => '1',
            'widget_title_en_US' => 'Category',
            'widget_title_ar' => 'اتصل بنا',
            'category_items' => '3',
        );

       return $data;

    }

    private function service_custom_form()
    {
        $data = array (
                'id' => '11',
                'widget_name' => 'TenantCustomFormWidget',
                'widget_type' => 'update',
                'widget_location' => 'service',
                'widget_order' => '2',
                'widget_title_en_US' => 'Have Query ?',
                'widget_title_ar' => 'اتصل بنا',
                'custom_form_id' => '2',
            );

        return $data;
    }



}
