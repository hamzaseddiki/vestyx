<?php

namespace Database\Seeders\Tenant\AllPages;

use App\Helpers\SeederHelpers\JsonDataModifier;
use App\Models\Page;
use App\Models\PageBuilder;
use Database\Seeders\Tenant\ModuleData\MenuSeed;

class DefaultPages
{

    public static function execute()
    {

            $payment_log = tenant()->payment_log()?->first() ?? [];
            $current_theme = $payment_log->theme;

            $object = new JsonDataModifier('', 'dynamic-pages');
            $data = $object->getColumnDataForDynamicPage([
                'id',
                'title',
                'page_content',
                'slug',
                'page_builder',
                'breadcrumb',
                'status',
                'theme_slug'
            ],true, true);

          //For home pages

        $filter_data = array_filter($data,function ($item) use ($current_theme){
            if (in_array($item['theme_slug'],[null,$current_theme])){
                return $item;
            }
        });
        $homepageData = array_filter($data,function ($item) use ($current_theme){
            if (in_array($item['theme_slug'],[$current_theme])){
                return $item;
            }
        });

        $homepageData = current($homepageData);

        $mapped_data = array_map(function ($item){
                unset($item['theme_slug']);
            return $item;
        },$filter_data);

            Page::insert($mapped_data);

            $homepage_id = $homepageData['id'] ?? null;
            $home_page_layout_file = $payment_log->theme.'-layout.json';

            self::upload_layout($home_page_layout_file, $homepage_id);

            try {
                MenuSeed::execute($homepage_id);
            }catch (\Exception $e){

            }

            update_static_option('home_page',$homepage_id);

    }


    private static function upload_layout($file, $page_id)
    {
        $file_contents =  json_decode(file_get_contents('assets/tenant/page-layout/home-pages/'.$file));
        $file_contents = $file_contents->data ?? $file_contents;

        $contentArr = [];
        if (current($file_contents ?? [])->addon_page_type == 'dynamic_page')
        {
            foreach ($file_contents as $key => $content)
            {
                unset($content->id);
                $content->addon_page_id = (int)trim($page_id);
                $content->created_at = now();
                $content->updated_at = now();

                foreach ($content as $key2 => $con)
                {
                    $contentArr[$key][$key2] = $con;
                }
            }

            Page::findOrFail($page_id)->update(['page_builder' => 1]);
            PageBuilder::where('addon_page_id', $page_id)->delete();

            PageBuilder::insert($contentArr);

        } else {
            Page::findOrFail($page_id)->update([
                'page_builder' => 0,
                'page_content' => current($file_contents)->text
            ]);
        }
    }
}
