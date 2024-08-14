<?php

namespace App\Traits\SeederMethods;

use App\Helpers\FlashMsg;
use App\Helpers\SeederHelpers\JsonDataModifier;
use Illuminate\Http\Request;
use net\authorize\api\constants\ANetEnvironment;

trait WidgetSeederMethod
{
    private string $widget_json_base_path = 'assets/tenant/page-layout/widgets.json';

    public function widget_index()
    {
        return view(self::BASE_PATH.'widgets.widget-index');
    }

// Blog Widget
    public function blog_category_widget_data_settings(Request $request)
    {
        if (!file_exists($this->widget_json_base_path) && !is_dir($this->widget_json_base_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $path = new JsonDataModifier('','widgets');
        $all_data_decoded = $path->getColumnDataForWidget(column: 'widget_content',id: 4,serialize: false,instance:false);

        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'widgets.blog.category-data',compact(
            'all_data_decoded','default_lang'
        ));
    }

    public function update_blog_category_widget_data_settings(Request $request)
    {
        $request->validate(['title' => 'required']);

        $title = $request->title;
        $lang = $request->lang;

        $path = new JsonDataModifier('','widgets');
        $path->getColumnDataForWidget(column: 'widget_content',id: 4,serialize: false,instance:true)
            ->replaceByColumn(["title" => $title],$lang)->saveWidgetFile();

        return redirect()->back()->with(FlashMsg::item_update());

    }

    public function popular_blog_widget_data_settings(Request $request)
    {

        if (!file_exists($this->widget_json_base_path) && !is_dir($this->widget_json_base_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $path = new JsonDataModifier('','widgets');
        $all_data_decoded = $path->getColumnDataForWidget(column: 'widget_content',id: 5,serialize: false,instance:false);


        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'widgets.blog.popular-blog-data',compact(
            'all_data_decoded','default_lang'
        ));
    }

    public function update_popular_blog_widget_data_settings(Request $request)
    {
        $request->validate(['title' => 'required']);

        $title = $request->title;
        $lang = $request->lang;

        $path = new JsonDataModifier('','widgets');
        $path->getColumnDataForWidget(column: 'widget_content',id: 5,serialize: false,instance:true)
            ->replaceByColumn(["heading_text" => $title],$lang)->saveWidgetFile();

        return redirect()->back()->with(FlashMsg::item_update());
    }
// Blog Widget


// Service Widget
    public function service_category_widget_data_settings(Request $request)
    {
        if (!file_exists($this->widget_json_base_path) && !is_dir($this->widget_json_base_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $path = new JsonDataModifier('','widgets');
        $all_data_decoded = $path->getColumnDataForWidget(column: 'widget_content',id: 6,serialize: false,instance:false);

        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'widgets.service.category-data',compact(
            'all_data_decoded','default_lang'
        ));
    }

    public function update_service_category_widget_data_settings(Request $request)
    {
        $request->validate(['title' => 'required']);

        $title = $request->title;
        $lang = $request->lang;

        $path = new JsonDataModifier('','widgets');
        $path->getColumnDataForWidget(column: 'widget_content',id: 6,serialize: false,instance:true)
            ->replaceByColumn(["title" => $title],$lang)->saveWidgetFile();

        return redirect()->back()->with(FlashMsg::item_update());

    }

    public function popular_service_widget_data_settings(Request $request)
    {

        if (!file_exists($this->widget_json_base_path) && !is_dir($this->widget_json_base_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $path = new JsonDataModifier('','widgets');
        $all_data_decoded = $path->getColumnDataForWidget(column: 'widget_content',id: 7,serialize: false,instance:false);


        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'widgets.service.popular-service-data',compact(
            'all_data_decoded','default_lang'
        ));
    }

    public function update_popular_service_widget_data_settings(Request $request)
    {
        $request->validate(['title' => 'required']);

        $title = $request->title;
        $lang = $request->lang;

        $path = new JsonDataModifier('','widgets');
        $path->getColumnDataForWidget(column: 'widget_content',id: 7,serialize: false,instance:true)
            ->replaceByColumn(["heading_text" => $title],$lang)->saveWidgetFile();

        return redirect()->back()->with(FlashMsg::item_update());
    }


    public function query_submit_widget_data_settings(Request $request)
    {
        if (!file_exists($this->widget_json_base_path) && !is_dir($this->widget_json_base_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $path = new JsonDataModifier('','widgets');
        $all_data_decoded = $path->getColumnDataForWidget(column: 'widget_content',id: 8,serialize: false,instance:false);


        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'widgets.service.query-submit-data',compact(
            'all_data_decoded','default_lang'
        ));
    }

    public function update_query_submit_widget_data_settings(Request $request)
    {
        $request->validate(['title' => 'required']);

        $title = $request->title;
        $lang = $request->lang;

        $path = new JsonDataModifier('','widgets');
        $path->getColumnDataForWidget(column: 'widget_content',id: 8,serialize: false,instance:true)
            ->replaceByColumn(["heading_text" => $title],$lang)->saveWidgetFile();

        return redirect()->back()->with(FlashMsg::item_update());
    }
// Service Widget
    public function about_us_widget_data_settings(Request $request)
    {
        if (!file_exists($this->widget_json_base_path) && !is_dir($this->widget_json_base_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $path = new JsonDataModifier('','widgets');
        $all_data_decoded = $path->getColumnDataForWidget(column: 'widget_content',id: 19,serialize: false,instance:false);



        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'widgets.about-us-data',compact(
            'all_data_decoded','default_lang'
        ));
    }

    public function update_about_us_widget_data_settings(Request $request)
    {
        $request->validate(['description' => 'required']);

        $description = $request->description;
        $lang = $request->lang;

        $path = new JsonDataModifier('','widgets');

        //donation
        $path->getColumnDataForWidget(column: 'widget_content',id: 19,serialize: false,instance:true)
            ->replaceByColumn(["description" => purify_html($description)],$lang)->saveWidgetFile();

        dd();

        //event
        $path->getColumnDataForWidget(column: 'widget_content',id: 23,serialize: false,instance:true)
            ->replaceByColumn(["description" => purify_html($description)],$lang)->saveWidgetFile();

        //Job
        $path->getColumnDataForWidget(column: 'widget_content',id: 28,serialize: false,instance:true)
            ->replaceByColumn(["description" => purify_html($description)],$lang)->saveWidgetFile();

        //Article/Knowledgebase
        $path->getColumnDataForWidget(column: 'widget_content',id: 32,serialize: false,instance:true)
            ->replaceByColumn(["description" => purify_html($description)],$lang)->saveWidgetFile();

        //Support Ticket
        $path->getColumnDataForWidget(column: 'widget_content',id: 36,serialize: false,instance:true)
            ->replaceByColumn(["description" => purify_html($description)],$lang)->saveWidgetFile();

        //Ecommerce
        $path->getColumnDataForWidget(column: 'widget_content',id: 40,serialize: false,instance:true)
            ->replaceByColumn(["description" => purify_html($description)],$lang)->saveWidgetFile();

        //Agency
        $path->getColumnDataForWidget(column: 'widget_content',id: 44,serialize: false,instance:true)
            ->replaceByColumn(["description" => purify_html($description)],$lang)->saveWidgetFile();

        //Newspaper
        $path->getColumnDataForWidget(column: 'widget_content',id: 54,serialize: false,instance:true)
            ->replaceByColumn(["description" => purify_html($description)],$lang)->saveWidgetFile();

        //Construction
        $path->getColumnDataForWidget(column: 'widget_content',id: 59,serialize: false,instance:true)
            ->replaceByColumn(["description" => purify_html($description)],$lang)->saveWidgetFile();

        //Consultancy
        $path->getColumnDataForWidget(column: 'widget_content',id: 64,serialize: false,instance:true)
            ->replaceByColumn(["description" => purify_html($description)],$lang)->saveWidgetFile();

        //Wedding
        $path->getColumnDataForWidget(column: 'widget_content',id: 67,serialize: false,instance:true)
            ->replaceByColumn(["description" => purify_html($description)],$lang)->saveWidgetFile();

        //Photography
        $path->getColumnDataForWidget(column: 'widget_content',id: 73,serialize: false,instance:true)
            ->replaceByColumn(["description" => purify_html($description)],$lang)->saveWidgetFile();

        //Portfolio
        $path->getColumnDataForWidget(column: 'widget_content',id: 76,serialize: false,instance:true)
            ->replaceByColumn(["description" => purify_html($description)],$lang)->saveWidgetFile();

        //Software business
        $path->getColumnDataForWidget(column: 'widget_content',id: 80,serialize: false,instance:true)
            ->replaceByColumn(["description" => purify_html($description)],$lang)->saveWidgetFile();

        return redirect()->back()->with(FlashMsg::item_update());
    }

    public function navigation_widget_data_settings(Request $request)
    {

        if (!file_exists($this->widget_json_base_path) && !is_dir($this->widget_json_base_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $path = new JsonDataModifier('','widgets');
        $all_data_decoded = $path->getColumnDataForWidget(column: 'widget_content',id: 20,serialize: false,instance:false);

        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'widgets.navigation-menu-data',compact(
            'all_data_decoded','default_lang'
        ));
    }
    public function update_navigation_widget_data_settings(Request $request)
    {
        $request->validate(['title' => 'required']);

        $title = $request->title;
        $lang = $request->lang;

        $path = new JsonDataModifier('','widgets');

        //donation
        $path->getColumnDataForWidget(column: 'widget_content',id: 20,serialize: false,instance:true)
            ->replaceByColumn(["widget_title" => purify_html($title)],$lang)->saveWidgetFile();

        //event
        $path->getColumnDataForWidget(column: 'widget_content',id: 26,serialize: false,instance:true)
            ->replaceByColumn(["widget_title" => purify_html($title)],$lang)->saveWidgetFile();

        //job
        $path->getColumnDataForWidget(column: 'widget_content',id: 29,serialize: false,instance:true)
            ->replaceByColumn(["widget_title" => purify_html($title)],$lang)->saveWidgetFile();

        //Article / Knowledgebase
        $path->getColumnDataForWidget(column: 'widget_content',id: 33,serialize: false,instance:true)
            ->replaceByColumn(["widget_title" => purify_html($title)],$lang)->saveWidgetFile();

        //Ticket
        $path->getColumnDataForWidget(column: 'widget_content',id: 37,serialize: false,instance:true)
            ->replaceByColumn(["widget_title" => purify_html($title)],$lang)->saveWidgetFile();


        return redirect()->back()->with(FlashMsg::item_update());
    }

    public function custom_link_widget_data_settings(Request $request)
    {

        if (!file_exists($this->widget_json_base_path) && !is_dir($this->widget_json_base_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $path = new JsonDataModifier('','widgets');
        $all_data_decoded = $path->getColumnDataForWidget(column: 'widget_content',id: 21,serialize: false,instance:false);

        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'widgets.custom-link-data',compact(
            'all_data_decoded','default_lang'
        ));
    }
    public function update_custom_link_widget_data_settings(Request $request)
    {

        $request->validate(['title' => 'required']);

        $title = $request->title;
        $lang = $request->lang;

        $path = new JsonDataModifier('','widgets');

        //donation
        $path->getColumnDataForWidget(column: 'widget_content',id: 21,serialize: false,instance:true)
            ->replaceByColumn(["title" => purify_html($title)],$lang)->saveWidgetFile();

        //job
        $path->getColumnDataForWidget(column: 'widget_content',id: 30,serialize: false,instance:true)
            ->replaceByColumn(["title" => purify_html($title)],$lang)->saveWidgetFile();

        //Ticket
        $path->getColumnDataForWidget(column: 'widget_content',id: 38,serialize: false,instance:true)
            ->replaceByColumn(["title" => purify_html($title)],$lang)->saveWidgetFile();

        //Ecommerce 1
        $path->getColumnDataForWidget(column: 'widget_content',id: 41,serialize: false,instance:true)
            ->replaceByColumn(["title" => purify_html($title)],$lang)->saveWidgetFile();

        //Ecommerce 2
        $path->getColumnDataForWidget(column: 'widget_content',id: 42,serialize: false,instance:true)
            ->replaceByColumn(["title" => purify_html($title)],$lang)->saveWidgetFile();

        //Agency 1
        $path->getColumnDataForWidget(column: 'widget_content',id: 45,serialize: false,instance:true)
            ->replaceByColumn(["title" => purify_html($title)],$lang)->saveWidgetFile();

        //Agency 2
        $path->getColumnDataForWidget(column: 'widget_content',id: 46,serialize: false,instance:true)
            ->replaceByColumn(["title" => purify_html($title)],$lang)->saveWidgetFile();

        //Newspaper
        $path->getColumnDataForWidget(column: 'widget_content',id: 57,serialize: false,instance:true)
            ->replaceByColumn(["title" => purify_html($title)],$lang)->saveWidgetFile();

        //Construction 1
        $path->getColumnDataForWidget(column: 'widget_content',id: 60,serialize: false,instance:true)
            ->replaceByColumn(["title" => purify_html($title)],$lang)->saveWidgetFile();

        //Construction 2
        $path->getColumnDataForWidget(column: 'widget_content',id: 61,serialize: false,instance:true)
            ->replaceByColumn(["title" => purify_html($title)],$lang)->saveWidgetFile();


        //Consultancy 1
        $path->getColumnDataForWidget(column: 'widget_content',id: 65,serialize: false,instance:true)
            ->replaceByColumn(["title" => purify_html($title)],$lang)->saveWidgetFile();

        //Consultancy 2
        $path->getColumnDataForWidget(column: 'widget_content',id: 66,serialize: false,instance:true)
            ->replaceByColumn(["title" => purify_html($title)],$lang)->saveWidgetFile();

        //Wedding 1
        $path->getColumnDataForWidget(column: 'widget_content',id: 69,serialize: false,instance:true)
            ->replaceByColumn(["title" => purify_html($title)],$lang)->saveWidgetFile();

        //Wedding 2
        $path->getColumnDataForWidget(column: 'widget_content',id: 70,serialize: false,instance:true)
            ->replaceByColumn(["title" => purify_html($title)],$lang)->saveWidgetFile();


        //Wedding 3
        $path->getColumnDataForWidget(column: 'widget_content',id: 71,serialize: false,instance:true)
            ->replaceByColumn(["title" => purify_html($title)],$lang)->saveWidgetFile();

        //Photography
        $path->getColumnDataForWidget(column: 'widget_content',id: 74,serialize: false,instance:true)
            ->replaceByColumn(["title" => purify_html($title)],$lang)->saveWidgetFile();

        //Portfolio 1
        $path->getColumnDataForWidget(column: 'widget_content',id: 78,serialize: false,instance:true)
            ->replaceByColumn(["title" => purify_html($title)],$lang)->saveWidgetFile();

        //Portfolio 2
        $path->getColumnDataForWidget(column: 'widget_content',id: 79,serialize: false,instance:true)
            ->replaceByColumn(["title" => purify_html($title)],$lang)->saveWidgetFile();

        //Software Business 1
        $path->getColumnDataForWidget(column: 'widget_content',id: 81,serialize: false,instance:true)
            ->replaceByColumn(["title" => purify_html($title)],$lang)->saveWidgetFile();

        //Software Business 2
        $path->getColumnDataForWidget(column: 'widget_content',id: 82,serialize: false,instance:true)
            ->replaceByColumn(["title" => purify_html($title)],$lang)->saveWidgetFile();

        return redirect()->back()->with(FlashMsg::item_update());
    }

    public function contact_info_widget_data_settings(Request $request)
    {

        if (!file_exists($this->widget_json_base_path) && !is_dir($this->widget_json_base_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $path = new JsonDataModifier('','widgets');
        $all_data_decoded = $path->getColumnDataForWidget(column: 'widget_content',id: 22,serialize: false,instance:false);

        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'widgets.contact-info-data',compact(
            'all_data_decoded','default_lang'
        ));
    }
    public function update_contact_info_widget_data_settings(Request $request)
    {

        $request->validate(['title' => 'required']);

        $title = $request->title;
        $location = $request->location;
        $email = $request->email;
        $phone = $request->phone;
        $lang = $request->lang;

        $path = new JsonDataModifier('','widgets');

//widget_title
        $demoData_array = ["widget_title" => purify_html($title),"title" => purify_html($title), "location" => purify_html($location), "email" => purify_html($email), "phone" => purify_html($phone)];
        //donation
        $path->getColumnDataForWidget(column: 'widget_content',id: 22,serialize: false,instance:true)
            ->replaceByColumn($demoData_array,$lang)->saveWidgetFile();

        //event
        $path->getColumnDataForWidget(column: 'widget_content',id: 27,serialize: false,instance:true)
            ->replaceByColumn($demoData_array,$lang)->saveWidgetFile();

        //job
        $path->getColumnDataForWidget(column: 'widget_content',id: 31,serialize: false,instance:true)
            ->replaceByColumn($demoData_array,$lang)->saveWidgetFile();

        //Article
        $path->getColumnDataForWidget(column: 'widget_content',id: 34,serialize: false,instance:true)
            ->replaceByColumn($demoData_array,$lang)->saveWidgetFile();

        //Ticket
        $path->getColumnDataForWidget(column: 'widget_content',id: 39,serialize: false,instance:true)
            ->replaceByColumn($demoData_array,$lang)->saveWidgetFile();

        //Ecommerce
        $path->getColumnDataForWidget(column: 'widget_content',id: 43,serialize: false,instance:true)
            ->replaceByColumn($demoData_array,$lang)->saveWidgetFile();

        //Agency
        $path->getColumnDataForWidget(column: 'widget_content',id: 47,serialize: false,instance:true)
            ->replaceByColumn($demoData_array,$lang)->saveWidgetFile();

        //Newspaper
        $path->getColumnDataForWidget(column: 'widget_content',id: 58,serialize: false,instance:true)
            ->replaceByColumn($demoData_array,$lang)->saveWidgetFile();

        //Construction
        $path->getColumnDataForWidget(column: 'widget_content',id: 62,serialize: false,instance:true)
            ->replaceByColumn($demoData_array,$lang)->saveWidgetFile();

        //Consultancy
        $path->getColumnDataForWidget(column: 'widget_content',id: 63,serialize: false,instance:true)
            ->replaceByColumn($demoData_array,$lang)->saveWidgetFile();

        //Wedding
        $path->getColumnDataForWidget(column: 'widget_content',id: 68,serialize: false,instance:true)
            ->replaceByColumn($demoData_array,$lang)->saveWidgetFile();

        //Photography
        $path->getColumnDataForWidget(column: 'widget_content',id: 75,serialize: false,instance:true)
            ->replaceByColumn($demoData_array,$lang)->saveWidgetFile();

        //Portfolio
        $path->getColumnDataForWidget(column: 'widget_content',id: 77,serialize: false,instance:true)
            ->replaceByColumn($demoData_array,$lang)->saveWidgetFile();

        //Software
        $path->getColumnDataForWidget(column: 'widget_content',id: 83,serialize: false,instance:true)
            ->replaceByColumn($demoData_array,$lang)->saveWidgetFile();


        return redirect()->back()->with(FlashMsg::item_update());
    }

    public function donation_category_widget_data_settings(Request $request)
    {
        if (!file_exists($this->widget_json_base_path) && !is_dir($this->widget_json_base_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $path = new JsonDataModifier('','widgets');
        $all_data_decoded = $path->getColumnDataForWidget(column: 'widget_content',id: 11,serialize: false,instance:false);

        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'widgets.donation.category-data',compact(
            'all_data_decoded','default_lang'
        ));
    }

    public function update_donation_category_widget_data_settings(Request $request)
    {
        $request->validate(['title' => 'required']);

        $title = $request->title;
        $lang = $request->lang;

        $path = new JsonDataModifier('','widgets');
        $path->getColumnDataForWidget(column: 'widget_content',id: 11,serialize: false,instance:true)
            ->replaceByColumn(["widget_title" => $title],$lang)->saveWidgetFile();

        return redirect()->back()->with(FlashMsg::item_update());

    }

    public function recent_donation_widget_data_settings(Request $request)
    {

        if (!file_exists($this->widget_json_base_path) && !is_dir($this->widget_json_base_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $path = new JsonDataModifier('','widgets');
        $all_data_decoded = $path->getColumnDataForWidget(column: 'widget_content',id: 12,serialize: false,instance:false);

        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'widgets.donation.recent-donation-data',compact(
            'all_data_decoded','default_lang'
        ));
    }

    public function update_recent_donation_widget_data_settings(Request $request)
    {
        $request->validate(['title' => 'required']);

        $title = $request->title;
        $lang = $request->lang;

        $path = new JsonDataModifier('','widgets');
        $path->getColumnDataForWidget(column: 'widget_content',id: 12,serialize: false,instance:true)
            ->replaceByColumn(["heading_text" => $title],$lang)->saveWidgetFile();

        return redirect()->back()->with(FlashMsg::item_update());

    }

    public function donation_activity_category_widget_data_settings(Request $request)
    {
        if (!file_exists($this->widget_json_base_path) && !is_dir($this->widget_json_base_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $path = new JsonDataModifier('','widgets');
        $all_data_decoded = $path->getColumnDataForWidget(column: 'widget_content',id: 10,serialize: false,instance:false);

        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'widgets.donation.activity-category-data',compact(
            'all_data_decoded','default_lang'
        ));
    }

    public function update_donation_activity_category_widget_data_settings(Request $request)
    {
        $request->validate(['title' => 'required']);

        $title = $request->title;
        $lang = $request->lang;

        $path = new JsonDataModifier('','widgets');
        $path->getColumnDataForWidget(column: 'widget_content',id: 10,serialize: false,instance:true)
            ->replaceByColumn(["widget_title" => $title],$lang)->saveWidgetFile();

        return redirect()->back()->with(FlashMsg::item_update());

    }

    public function recent_donation_activities_widget_data_settings(Request $request)
    {

        if (!file_exists($this->widget_json_base_path) && !is_dir($this->widget_json_base_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $path = new JsonDataModifier('','widgets');
        $all_data_decoded = $path->getColumnDataForWidget(column: 'widget_content',id: 9,serialize: false,instance:false);

        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'widgets.donation.recent-donation-activity-data',compact(
            'all_data_decoded','default_lang'
        ));
    }

    public function update_recent_donation_activities_widget_data_settings(Request $request)
    {
        $request->validate(['title' => 'required']);

        $title = $request->title;
        $lang = $request->lang;

        $path = new JsonDataModifier('','widgets');
        $path->getColumnDataForWidget(column: 'widget_content',id: 9,serialize: false,instance:true)
            ->replaceByColumn(["heading_text" => $title],$lang)->saveWidgetFile();

        return redirect()->back()->with(FlashMsg::item_update());

    }

    public function footer_recent_event_widget_data_settings(Request $request)
    {
        if (!file_exists($this->widget_json_base_path) && !is_dir($this->widget_json_base_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $path = new JsonDataModifier('','widgets');
        $all_data_decoded = $path->getColumnDataForWidget(column: 'widget_content',id: 25,serialize: false,instance:false);

        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'widgets.event.footer-recent-event-data',compact(
            'all_data_decoded','default_lang'
        ));
    }

    public function update_footer_recent_event_widget_data_settings(Request $request)
    {
        $request->validate(['title' => 'required']);

        $title = $request->title;
        $lang = $request->lang;

        $path = new JsonDataModifier('','widgets');
        $path->getColumnDataForWidget(column: 'widget_content',id: 25,serialize: false,instance:true)
            ->replaceByColumn(["heading_text" => $title],$lang)->saveWidgetFile();

        return redirect()->back()->with(FlashMsg::item_update());

    }

    public function event_category_widget_data_settings(Request $request)
    {
        if (!file_exists($this->widget_json_base_path) && !is_dir($this->widget_json_base_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $path = new JsonDataModifier('','widgets');
        $all_data_decoded = $path->getColumnDataForWidget(column: 'widget_content',id:13,serialize: false,instance:false);

        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'widgets.event.category-data',compact(
            'all_data_decoded','default_lang'
        ));
    }

    public function update_event_category_widget_data_settings(Request $request)
    {
        $request->validate(['title' => 'required']);

        $title = $request->title;
        $lang = $request->lang;

        $path = new JsonDataModifier('','widgets');
        $path->getColumnDataForWidget(column: 'widget_content',id: 13,serialize: false,instance:true)
            ->replaceByColumn(["title" => $title],$lang)->saveWidgetFile();

        return redirect()->back()->with(FlashMsg::item_update());

    }

    public function sidebar_recent_event_widget_data_settings(Request $request)
    {
        if (!file_exists($this->widget_json_base_path) && !is_dir($this->widget_json_base_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $path = new JsonDataModifier('','widgets');
        $all_data_decoded = $path->getColumnDataForWidget(column: 'widget_content',id: 14,serialize: false,instance:false);

        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'widgets.event.sidebar-recent-event-data',compact(
            'all_data_decoded','default_lang'
        ));
    }

    public function update_sidebar_recent_event_widget_data_settings(Request $request)
    {
        $request->validate(['title' => 'required']);

        $title = $request->title;
        $lang = $request->lang;

        $path = new JsonDataModifier('','widgets');
        $path->getColumnDataForWidget(column: 'widget_content',id: 14,serialize: false,instance:true)
            ->replaceByColumn(["heading_text" => $title],$lang)->saveWidgetFile();

        return redirect()->back()->with(FlashMsg::item_update());

    }

    public function job_category_widget_data_settings(Request $request)
    {
        if (!file_exists($this->widget_json_base_path) && !is_dir($this->widget_json_base_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $path = new JsonDataModifier('','widgets');
        $all_data_decoded = $path->getColumnDataForWidget(column: 'widget_content',id:15,serialize: false,instance:false);

        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'widgets.job.category-data',compact(
            'all_data_decoded','default_lang'
        ));
    }

    public function update_job_category_widget_data_settings(Request $request)
    {
        $request->validate(['title' => 'required']);

        $title = $request->title;
        $lang = $request->lang;

        $path = new JsonDataModifier('','widgets');
        $path->getColumnDataForWidget(column: 'widget_content',id: 15,serialize: false,instance:true)
            ->replaceByColumn(["title" => $title],$lang)->saveWidgetFile();

        return redirect()->back()->with(FlashMsg::item_update());

    }

    public function recent_job_widget_data_settings(Request $request)
    {
        if (!file_exists($this->widget_json_base_path) && !is_dir($this->widget_json_base_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $path = new JsonDataModifier('','widgets');
        $all_data_decoded = $path->getColumnDataForWidget(column: 'widget_content',id: 16,serialize: false,instance:false);

        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'widgets.job.recent-job-data',compact(
            'all_data_decoded','default_lang'
        ));
    }

    public function update_recent_job_widget_data_settings(Request $request)
    {
        $request->validate(['title' => 'required']);

        $title = $request->title;
        $lang = $request->lang;

        $path = new JsonDataModifier('','widgets');
        $path->getColumnDataForWidget(column: 'widget_content',id: 16,serialize: false,instance:true)
            ->replaceByColumn(["heading_text" => $title],$lang)->saveWidgetFile();

        return redirect()->back()->with(FlashMsg::item_update());

    }

    public function article_newsletter_widget_data_settings(Request $request)
    {
        if (!file_exists($this->widget_json_base_path) && !is_dir($this->widget_json_base_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $path = new JsonDataModifier('','widgets');
        $all_data_decoded = $path->getColumnDataForWidget(column: 'widget_content',id: 35,serialize: false,instance:false);

        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'widgets.article.subscribe-newsletter-data',compact(
            'all_data_decoded','default_lang'
        ));
    }

    public function update_article_newsletter_widget_data_settings(Request $request)
    {
        $request->validate([
            'heading_text' => 'required',
            'description' => 'required'
        ]);

        $heading_text = $request->heading_text;
        $description = purify_html($request->description);
        $lang = $request->lang;

        $path = new JsonDataModifier('','widgets');
        $path->getColumnDataForWidget(column: 'widget_content',id: 35,serialize: false,instance:true)
            ->replaceByColumn(["heading_text" => $heading_text, 'description'=>$description],$lang)->saveWidgetFile();

        return redirect()->back()->with(FlashMsg::item_update());

    }

    public function article_category_widget_data_settings(Request $request)
    {
        if (!file_exists($this->widget_json_base_path) && !is_dir($this->widget_json_base_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $path = new JsonDataModifier('','widgets');
        $all_data_decoded = $path->getColumnDataForWidget(column: 'widget_content',id:18,serialize: false,instance:false);

        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'widgets.article.category-data',compact(
            'all_data_decoded','default_lang'
        ));
    }

    public function update_article_category_widget_data_settings(Request $request)
    {
        $request->validate(['title' => 'required']);

        $title = $request->title;
        $lang = $request->lang;

        $path = new JsonDataModifier('','widgets');
        $path->getColumnDataForWidget(column: 'widget_content',id: 18,serialize: false,instance:true)
            ->replaceByColumn(["title" => $title],$lang)->saveWidgetFile();

        return redirect()->back()->with(FlashMsg::item_update());

    }

    public function recent_article_widget_data_settings(Request $request)
    {
        if (!file_exists($this->widget_json_base_path) && !is_dir($this->widget_json_base_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $path = new JsonDataModifier('','widgets');
        $all_data_decoded = $path->getColumnDataForWidget(column: 'widget_content',id:17,serialize: false,instance:false);

        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'widgets.article.recent-article-data',compact(
            'all_data_decoded','default_lang'
        ));
    }

    public function update_recent_article_widget_data_settings(Request $request)
    {
        $request->validate(['title' => 'required']);

        $title = $request->title;
        $lang = $request->lang;

        $path = new JsonDataModifier('','widgets');
        $path->getColumnDataForWidget(column: 'widget_content',id: 17,serialize: false,instance:true)
            ->replaceByColumn(["heading_text" => $title],$lang)->saveWidgetFile();

        return redirect()->back()->with(FlashMsg::item_update());

    }

}
