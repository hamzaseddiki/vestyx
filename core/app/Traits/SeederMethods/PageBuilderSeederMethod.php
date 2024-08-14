<?php

namespace App\Traits\SeederMethods;

use App\Helpers\FlashMsg;
use App\Helpers\SeederHelpers\JsonDataModifier;
use Illuminate\Http\Request;

trait PageBuilderSeederMethod
{

    private string $page_builder_donation_home_path = 'assets/tenant/page-layout/home-pages/donation-layout.json';
    public function page_builder_index()
    {
        return view(self::BASE_PATH.'page-builder.page-builder-index');
    }

    public function donation_home_page_header_data_settings(Request $request)
    {
        $path = new JsonDataModifier('home-pages','donation-layout');
        $all_data_decoded = $path->getColumnDataForPageBuilder(column: 'addon_settings',id: 17,json_encode: false,instance:false);
        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'page-builder.donation-home.header-area-data',compact(
            'all_data_decoded','default_lang'
        ));

    }

    public function update_donation_home_page_header_data_settings(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'button_text' => 'required',
            'button_right_text' => 'required',
        ]);
        if (!file_exists($this->page_builder_donation_home_path) && !is_dir($this->page_builder_donation_home_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $title = $request->title;
        $description = purify_html($request->description);
        $button_text = $request->button_text;
        $button_right_text = $request->button_right_text;
        $lang = $request->lang;


        $path = new JsonDataModifier('home-pages','donation-layout');
        $path->getColumnDataForPageBuilder(column: 'addon_settings',id: 17,json_encode: false,instance:true)
            ->replaceByColumn([
                "title" => $title,
                "description" => $description,
                "button_text" => $button_text,
                "button_right_text" => $button_right_text,
            ],$lang,'json')
            ->savePageBuilderFile();

        return redirect()->back()->with(FlashMsg::item_update());
    }

    public function donation_home_page_brand_data_settings(Request $request)
    {
        $path = new JsonDataModifier('home-pages','donation-layout');
        $all_data_decoded = $path->getColumnDataForPageBuilder(column: 'addon_settings',id: 18,json_encode: false,instance:false);
        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'page-builder.donation-home.brand-area-data',compact(
            'all_data_decoded','default_lang'
        ));

    }

    public function update_donation_home_page_brand_data_settings(Request $request)
    {
        $request->validate(['title' => 'required']);

        if (!file_exists($this->page_builder_donation_home_path) && !is_dir($this->page_builder_donation_home_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $title = $request->title;
        $lang = $request->lang;

        $path = new JsonDataModifier('home-pages','donation-layout');
        $path->getColumnDataForPageBuilder(column: 'addon_settings',id: 18,json_encode: false,instance:true)
            ->replaceByColumn(["title" => $title,],$lang,'json')
            ->savePageBuilderFile();

        return redirect()->back()->with(FlashMsg::item_update());
    }

    public function donation_home_page_campaign_data_settings(Request $request)
    {
        $path = new JsonDataModifier('home-pages','donation-layout');
        $all_data_decoded = $path->getColumnDataForPageBuilder(column: 'addon_settings',id: 19,json_encode: false,instance:false);
        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'page-builder.donation-home.campaign-area-data',compact(
            'all_data_decoded','default_lang'
        ));

    }

    public function update_donation_home_page_campaign_data_settings(Request $request)
    {
        $request->validate(['title' => 'required']);

        if (!file_exists($this->page_builder_donation_home_path) && !is_dir($this->page_builder_donation_home_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $title = $request->title;
        $lang = $request->lang;

        $path = new JsonDataModifier('home-pages','donation-layout');
        $path->getColumnDataForPageBuilder(column: 'addon_settings',id: 19,json_encode: false,instance:true)
            ->replaceByColumn(["title" => $title,],$lang,'json')
            ->savePageBuilderFile();

        return redirect()->back()->with(FlashMsg::item_update());
    }

    public function donation_home_page_about_data_settings(Request $request)
    {
        $path = new JsonDataModifier('home-pages','donation-layout');
        $all_data_decoded = $path->getColumnDataForPageBuilder(column: 'addon_settings',id: 20,json_encode: false,instance:false);
        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'page-builder.donation-home.about-area-data',compact(
            'all_data_decoded','default_lang'
        ));

    }

    public function update_donation_home_page_about_data_settings(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'button_text' => 'required',
        ]);
        if (!file_exists($this->page_builder_donation_home_path) && !is_dir($this->page_builder_donation_home_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $title = $request->title;
        $description = purify_html($request->description);
        $button_text = $request->button_text;
        $lang = $request->lang;


        $path = new JsonDataModifier('home-pages','donation-layout');
        $path->getColumnDataForPageBuilder(column: 'addon_settings',id: 20,json_encode: false,instance:true)
            ->replaceByColumn([
                "title" => $title,
                "description" => $description,
                "button_text" => $button_text,
            ],$lang,'json')
            ->savePageBuilderFile();

        return redirect()->back()->with(FlashMsg::item_update());
    }

    public function donation_home_page_campaign_two_data_settings(Request $request)
    {
        $path = new JsonDataModifier('home-pages','donation-layout');
        $all_data_decoded = $path->getColumnDataForPageBuilder(column: 'addon_settings',id: 21,json_encode: false,instance:false);
        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'page-builder.donation-home.campaign-area-two-data',compact(
            'all_data_decoded','default_lang'
        ));

    }

    public function update_donation_home_page_campaign_two_data_settings(Request $request)
    {
        $request->validate(['title' => 'required']);

        if (!file_exists($this->page_builder_donation_home_path) && !is_dir($this->page_builder_donation_home_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $title = $request->title;
        $lang = $request->lang;

        $path = new JsonDataModifier('home-pages','donation-layout');
        $path->getColumnDataForPageBuilder(column: 'addon_settings',id: 21,json_encode: false,instance:true)
            ->replaceByColumn(["title" => $title,],$lang,'json')
            ->savePageBuilderFile();

        return redirect()->back()->with(FlashMsg::item_update());
    }

    public function donation_home_page_activities_data_settings(Request $request)
    {
        $path = new JsonDataModifier('home-pages','donation-layout');
        $all_data_decoded = $path->getColumnDataForPageBuilder(column: 'addon_settings',id: 23,json_encode: false,instance:false);
        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'page-builder.donation-home.activities-area-data',compact(
            'all_data_decoded','default_lang'
        ));

    }

    public function update_donation_home_page_activities_data_settings(Request $request)
    {
        $request->validate(['title' => 'required']);

        if (!file_exists($this->page_builder_donation_home_path) && !is_dir($this->page_builder_donation_home_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $title = $request->title;
        $lang = $request->lang;

        $path = new JsonDataModifier('home-pages','donation-layout');
        $path->getColumnDataForPageBuilder(column: 'addon_settings',id: 23,json_encode: false,instance:true)
            ->replaceByColumn(["title" => $title,],$lang,'json')
            ->savePageBuilderFile();

        return redirect()->back()->with(FlashMsg::item_update());
    }

    public function donation_home_page_testimonial_data_settings(Request $request)
    {
        $path = new JsonDataModifier('home-pages','donation-layout');
        $all_data_decoded = $path->getColumnDataForPageBuilder(column: 'addon_settings',id: 24,json_encode: false,instance:false);
        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'page-builder.donation-home.testimonial-area-data',compact(
            'all_data_decoded','default_lang'
        ));

    }

    public function update_donation_home_page_testimonial_data_settings(Request $request)
    {
        $request->validate(['title' => 'required']);

        if (!file_exists($this->page_builder_donation_home_path) && !is_dir($this->page_builder_donation_home_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $title = $request->title;
        $lang = $request->lang;

        $path = new JsonDataModifier('home-pages','donation-layout');
        $path->getColumnDataForPageBuilder(column: 'addon_settings',id: 24,json_encode: false,instance:true)
            ->replaceByColumn(["title" => $title,],$lang,'json')
            ->savePageBuilderFile();

        return redirect()->back()->with(FlashMsg::item_update());
    }

    public function donation_home_page_blog_data_settings(Request $request)
    {
        $path = new JsonDataModifier('home-pages','donation-layout');
        $all_data_decoded = $path->getColumnDataForPageBuilder(column: 'addon_settings',id: 25,json_encode: false,instance:false);
        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'page-builder.donation-home.testimonial-area-data',compact(
            'all_data_decoded','default_lang'
        ));

    }

    public function update_donation_home_page_blog_data_settings(Request $request)
    {
        $request->validate(['title' => 'required']);

        if (!file_exists($this->page_builder_donation_home_path) && !is_dir($this->page_builder_donation_home_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $title = $request->title;
        $lang = $request->lang;

        $path = new JsonDataModifier('home-pages','donation-layout');
        $path->getColumnDataForPageBuilder(column: 'addon_settings',id: 25,json_encode: false,instance:true)
            ->replaceByColumn(["title" => $title,],$lang,'json')
            ->savePageBuilderFile();

        return redirect()->back()->with(FlashMsg::item_update());
    }

}
