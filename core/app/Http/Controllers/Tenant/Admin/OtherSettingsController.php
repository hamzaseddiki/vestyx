<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Facades\GlobalLanguage;
use App\Helpers\ResponseMessage;
use App\Helpers\SeederHelpers\JsonDataModifier;
use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\PageBuilder;
use App\Models\Tenant;
use App\Models\Themes;
use Database\Seeders\Tenant\AllPages\DefaultPages;
use Database\Seeders\Tenant\ModuleData\MenuSeed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OtherSettingsController extends Controller
{
    public function other_settings_page()
    {
        return view('tenant.admin.pages.other-settings');
    }

    public function update_other_settings(Request $request)
    {
        foreach (GlobalLanguage::all_languages() as $lang){

            $fields = [

                'donation_top_campaign_button_'.$lang->slug.'_text'  => 'nullable|string',
                'donation_top_campaign_button_'.$lang->slug.'_url' => 'nullable|string',

                'event_top_event_button_'.$lang->slug.'_text' => 'nullable|string',
                'event_top_event_button_'.$lang->slug.'_url' => 'nullable|string',

                'job_top_job_button_'.$lang->slug.'_text' => 'nullable|string',
                'job_top_job_button_'.$lang->slug.'_url' => 'nullable|string',

                'article_top_job_button_'.$lang->slug.'_text' => 'nullable|string',
                'article_top_job_button_'.$lang->slug.'_url' => 'nullable|string',

                'ticket_top_job_button_'.$lang->slug.'_text' => 'nullable|string',
                'ticket_top_job_button_'.$lang->slug.'_url' => 'nullable|string',

                'agency_top_contact_button_'.$lang->slug.'_text' => 'nullable|string',
                'agency_top_contact_button_'.$lang->slug.'_url' => 'nullable|string',

                'news_top_contact_button_'.$lang->slug.'_text' => 'nullable|string',
                'news_top_contact_button_'.$lang->slug.'_url' => 'nullable|string',
                'newspaper_top_leftbar' => 'nullable|string',

                'construction_top_contact_button_'.$lang->slug.'_text' => 'nullable|string',
                'construction_top_contact_button_'.$lang->slug.'_url' => 'nullable|string',

                'consultancy_top_contact_button_'.$lang->slug.'_text' => 'nullable|string',
                'consultancy_top_contact_button_'.$lang->slug.'_url' => 'nullable|string',

                'wedding_top_contact_button_'.$lang->slug.'_text' => 'nullable|string',
                'wedding_top_contact_button_'.$lang->slug.'_url' => 'nullable|string',

                'photography_top_contact_button_'.$lang->slug.'_text' => 'nullable|string',
                'photography_top_contact_button_'.$lang->slug.'_url' => 'nullable|string',

                'portfolio_top_contact_button_'.$lang->slug.'_text' => 'nullable|string',
                'portfolio_top_contact_button_'.$lang->slug.'_url' => 'nullable|string',

                'software_business_top_contact_button_'.$lang->slug.'_text' => 'nullable|string',
                'software_business_top_contact_button_'.$lang->slug.'_url' => 'nullable|string',

                'barber_shop_top_contact_button_'.$lang->slug.'_text' => 'nullable|string',
                'barber_shop_top_contact_button_'.$lang->slug.'_url' => 'nullable|string',
            ];



            $this->validate($request,$fields);

            foreach ($fields as $field_name => $rules){
                update_static_option($field_name,$request->$field_name);
            }
        }

        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function theme_settings()
    {
        $all_themes = Themes::where('status',1)->get();
        return view('tenant.admin.pages.theme-settings',compact('all_themes'));
    }

    public function update_theme_settings(Request $request)
    {
        $request->validate([
            'theme_setting_type' => 'required',
            'tenant_default_theme' => 'nullable',
        ],[
            'theme_setting_type.required' => __('Please select theme setting type by clicking on the theme image..!')
        ]);

        $theme_setting_type = $request->theme_setting_type;
        $requested_theme = $request->tenant_default_theme;

        if($theme_setting_type !== 'set_theme'){
            $this->set_new_home($requested_theme);
        }
        update_static_option('tenant_default_theme',$requested_theme);
        $tenant_id = \tenant()->id;
        Tenant::where('id', $tenant_id)->update([
            'theme_slug' => $requested_theme
        ]);

        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function set_new_home($requested_theme)
    {

        $current_theme = $requested_theme;

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
               if($item['theme_slug'] == $current_theme){
                    return $item;
               }
            }
        });


        $homepageData = current($filter_data);

        $mapped_data = array_map(function ($item){
            unset($item['theme_slug']);
            return $item;
        },$filter_data);


        $main_data = current($mapped_data);
        if(is_array($main_data)){
            if(Page::find($main_data['id']) == true){
                update_static_option('home_page',$homepageData['id'] );
                return redirect()->back()->with(['type'=>'success','msg'=> __('Home Already Imported')]);
            }

            Page::insert($main_data);

            $homepage_id = $homepageData['id'] ?? null;
            $home_page_layout_file = $current_theme.'-layout.json';
            $this->upload_layout($home_page_layout_file, $homepage_id);

            update_static_option('home_page',$homepage_id);
        }

    }
    private function upload_layout($file, $page_id)
    {
        $file_contents =  json_decode(file_get_contents('assets/tenant/page-layout/home-pages/'.$file));
        $file_contents = $file_contents->data ?? $file_contents;



        $contentArr = [];
        if (current($file_contents)->addon_page_type == 'dynamic_page')
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
