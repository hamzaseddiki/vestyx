<?php

namespace App\Http\Controllers\Landlord\Admin;

use App\Helpers\FlashMsg;
use App\Helpers\SeederHelpers\JsonDataModifier;
use App\Http\Controllers\Controller;
use App\Traits\SeederMethods\LanguageSeederMethod;
use App\Traits\SeederMethods\PageBuilderSeederMethod;
use App\Traits\SeederMethods\ProductSeederMethod;
use App\Traits\SeederMethods\TenantDemoDataSeederMethod;
use App\Traits\SeederMethods\WidgetSeederMethod;
use Illuminate\Http\Request;

class SeederSettingsController extends Controller
{
    use WidgetSeederMethod, TenantDemoDataSeederMethod,  LanguageSeederMethod, ProductSeederMethod, PageBuilderSeederMethod;
    private const BASE_PATH = 'landlord.admin.seeder-settings.';

 //Donation with activity
    public function donation_index()
    {
        return view(self::BASE_PATH.'donation.donation-index');
    }
    public function donation_category_data_settings(Request $request)
    {
        $only_path = 'assets/tenant/page-layout/donation/donation-category.json';
        if (!file_exists($only_path) && !is_dir($only_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/donation/donation-category.json');
        $all_data_decoded = json_decode($dynamic_pages);

        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'donation.donation-category-data',compact('all_data_decoded','default_lang'));
    }
    public function update_donation_category_data_settings(Request $request)
    {
        $request->validate(['title' => 'required']);

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/donation/donation-category.json');
        $only_path = 'assets/tenant/page-layout/donation/donation-category.json';

        $all_data_decoded = json_decode($dynamic_pages);

        $id = $request->id;
        $title = $request->title;
        $lang = $request->lang;

        foreach ($all_data_decoded->data ?? [] as $item) {
           if($item->id == $id){
               $decoded = (array) json_decode($item->title) ?? [];
               $decoded[$lang] = $title;
               $item->title = json_encode($decoded);
           }
        }

        file_put_contents($only_path ,json_encode($all_data_decoded));

        return redirect()->back()->with(FlashMsg::item_update());
    }
    public function donation_data_settings(Request $request)
    {
        $path = 'assets/tenant/page-layout/donation/donation.json';
        if (!file_exists($path) && !is_dir($path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/donation/donation.json');
        $all_data_decoded = json_decode($dynamic_pages);
        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'donation.donation-data',compact('all_data_decoded','default_lang'));
    }
    public function update_donation_data_settings(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/donation/donation.json');
        $only_path = 'assets/tenant/page-layout/donation/donation.json';

        $all_data_decoded = json_decode($dynamic_pages);

        $id = $request->id;
        $title = $request->title;
        $description = purify_html($request->description);
        $lang = $request->lang;


        foreach ($all_data_decoded->data ?? [] as $item) {
            if($item->id == $id){
                //title
                $decoded_title = (array) json_decode($item->title) ?? [];
                $decoded_title[$lang] = $title;
                $item->title = json_encode($decoded_title);

                //description
                $decoded_description = (array) json_decode($item->description) ?? [];
                $decoded_description[$lang] = $description;
                $item->description = json_encode($decoded_description);
            }
        }

        file_put_contents($only_path ,json_encode($all_data_decoded));

        return redirect()->back()->with(FlashMsg::item_update());
    }
    public function donation_activities_category_data_settings(Request $request)
    {
        $only_path = 'assets/tenant/page-layout/donation/donation-activities-category.json';
        if (!file_exists($only_path) && !is_dir($only_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $path = file_get_contents('assets/tenant/page-layout/donation/donation-activities-category.json');
        $all_data_decoded = json_decode($path) ?? [];
        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'donation.activities-category-data',compact('all_data_decoded','default_lang'));
    }
    public function update_activities_donation_category_data_settings(Request $request)
    {
        $request->validate(['title' => 'required']);

        $main_path = file_get_contents('assets/tenant/page-layout/donation/donation-activities-category.json');
        $only_path = 'assets/tenant/page-layout/donation/donation-activities-category.json';

        $all_data_decoded = json_decode($main_path);

        $id = $request->id;
        $title = $request->title;
        $lang = $request->lang;

        foreach ($all_data_decoded->data ?? [] as $item) {
            if($item->id == $id){
                $decoded = (array) json_decode($item->title) ?? [];
                $decoded[$lang] = $title;
                $item->title = json_encode($decoded);
            }
        }

        file_put_contents($only_path ,json_encode($all_data_decoded));

        return redirect()->back()->with(FlashMsg::item_update());
    }
    public function donation_activities_data_settings(Request $request)
    {

        $only_path = 'assets/tenant/page-layout/donation/donation-activities.json';
        if (!file_exists($only_path) && !is_dir($only_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/donation/donation-activities.json');
        $all_data_decoded = json_decode($dynamic_pages);
        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'donation.donation-activities-data',compact('all_data_decoded','default_lang'));
    }
    public function update_activities_donation_data_settings(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        $path = file_get_contents('assets/tenant/page-layout/donation/donation-activities.json');
        $only_path = 'assets/tenant/page-layout/donation/donation-activities.json';
        $all_data_decoded = json_decode($path);

        $id = $request->id;
        $title = $request->title;
        $description = purify_html($request->description);
        $lang = $request->lang;


        foreach ($all_data_decoded->data ?? [] as $item) {
            if($item->id == $id){
                //title
                $decoded_title = (array) json_decode($item->title) ?? [];
                $decoded_title[$lang] = $title;
                $item->title = json_encode($decoded_title);

                //description
                $decoded_description = (array) json_decode($item->description) ?? [];
                $decoded_description[$lang] = $description;
                $item->description = json_encode($decoded_description);
            }
        }

        file_put_contents($only_path ,json_encode($all_data_decoded));

        return redirect()->back()->with(FlashMsg::item_update());
    }
 //Donation with activity


//Event Data
    public function event_index()
    {
        return view(self::BASE_PATH.'event.event-index');
    }
    public function event_category_data_settings(Request $request)
    {
        $only_path = 'assets/tenant/page-layout/event/event-category.json';
        if (!file_exists($only_path) && !is_dir($only_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/event/event-category.json');
        $all_data_decoded = json_decode($dynamic_pages);
        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'event.category-data',compact('all_data_decoded','default_lang'));
    }
    public function update_event_category_data_settings(Request $request)
    {
        $request->validate(['title' => 'required']);

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/event/event-category.json');
        $only_path = 'assets/tenant/page-layout/event/event-category.json';
        $all_data_decoded = json_decode($dynamic_pages);

        $id = $request->id;
        $title = $request->title;
        $lang = $request->lang;

        foreach ($all_data_decoded->data ?? [] as $item) {
            if($item->id == $id){
                $decoded = (array) json_decode($item->title) ?? [];
                $decoded[$lang] = $title;
                $item->title = json_encode($decoded);
            }
        }

        file_put_contents($only_path ,json_encode($all_data_decoded));

        return redirect()->back()->with(FlashMsg::item_update());
    }
    public function event_data_settings(Request $request)
    {
        $only_path = 'assets/tenant/page-layout/event/event.json';
        if (!file_exists($only_path) && !is_dir($only_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/event/event.json');
        $all_data_decoded = json_decode($dynamic_pages);
        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'event.event-data',compact('all_data_decoded','default_lang'));
    }
    public function update_event_data_settings(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        $path = file_get_contents('assets/tenant/page-layout/event/event.json');
        $only_path = 'assets/tenant/page-layout/event/event.json';
        $all_data_decoded = json_decode($path);

        $id = $request->id;
        $title = $request->title;
        $description = purify_html($request->description);
        $lang = $request->lang;

        foreach ($all_data_decoded->data ?? [] as $item) {
            if($item->id == $id){
                //title
                $decoded_title = (array) json_decode($item->title) ?? [];
                $decoded_title[$lang] = $title;
                $item->title = json_encode($decoded_title);

                //description
                $decoded_description = (array) json_decode(get_data_without_extra_space_or_new_line($item->content)) ?? [];
                $decoded_description[$lang] = $description;
                $item->content = json_encode($decoded_description);
            }
        }

        file_put_contents($only_path ,json_encode($all_data_decoded));
        return redirect()->back()->with(FlashMsg::item_update());
    }
//Event Data


//Job Data
    public function job_index()
    {
        return view(self::BASE_PATH.'job.job-index');
    }
    public function job_category_data_settings(Request $request)
    {
        $only_path = 'assets/tenant/page-layout/job/job-category.json';
        if (!file_exists($only_path) && !is_dir($only_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/job/job-category.json');
        $all_data_decoded = json_decode($dynamic_pages);
        $default_lang = $request->lang ?? default_lang();
        return view(self::BASE_PATH.'job.category-data',compact('all_data_decoded','default_lang'));
    }
    public function update_job_category_data_settings(Request $request)
    {
        $request->validate(['title' => 'required']);
        $jsonModifier = new JsonDataModifier("job","job-category");

        $jsonModifier->replaceColumnContent(
            id: $request->id,
            columName: "title",
            newData: $request->title,
            lang: $request->lang
        );

        $jsonModifier->replaceColumnContent(
            id: $request->id,
            columName: "subtitle",
            newData: $request->subtitle,
            lang: $request->lang
        );

        $jsonModifier->saveFile(true);

        return redirect()->back()->with(FlashMsg::item_update());
    }
    public function job_data_settings(Request $request)
    {
        $only_path = 'assets/tenant/page-layout/job/job.json';
        if (!file_exists($only_path) && !is_dir($only_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/job/job.json');
        $all_data_decoded = json_decode($dynamic_pages);
        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'job.job-data',compact('all_data_decoded','default_lang'));
    }
    public function update_job_data_settings(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'designation' => 'required',
            'job_location' => 'required',
            'company_name' => 'required',
        ]);

        $path = file_get_contents('assets/tenant/page-layout/job/job.json');
        $only_path = 'assets/tenant/page-layout/job/job.json';
        $all_data_decoded = json_decode($path);

        $id = $request->id;
        $title = $request->title;
        $designation = $request->designation;
        $job_location = $request->job_location;
        $company_name = $request->company_name;
        $description = purify_html($request->description);
        $lang = $request->lang;


        foreach ($all_data_decoded->data ?? [] as $item) {
            if($item->id == $id){

                //title
                $decoded_title = (array) json_decode($item->title) ?? [];
                $decoded_title[$lang] = $title;
                $item->title = json_encode($decoded_title);

                //designation
                $decoded_designation = (array) json_decode($item->designation) ?? [];
                $decoded_designation[$lang] = $designation;
                $item->designation = json_encode($decoded_designation);

                //job location
                $decoded_job_location = (array) json_decode($item->job_location) ?? [];
                $decoded_job_location[$lang] = $job_location;
                $item->job_location = json_encode($decoded_job_location);

                //company name
                $decoded_company_name = (array) json_decode($item->company_name) ?? [];
                $decoded_company_name[$lang] = $company_name;
                $item->company_name = json_encode($decoded_company_name);

                //description
                $decoded_description = (array) json_decode(get_data_without_extra_space_or_new_line($item->description)) ?? [];
                $decoded_description[$lang] = $description;
                $item->description = json_encode($decoded_description);
            }
        }

        file_put_contents($only_path ,json_encode($all_data_decoded));

        return redirect()->back()->with(FlashMsg::item_update());
    }
//Job Data

//Portfolio Data
    public function portfolio_index()
    {
        return view(self::BASE_PATH.'portfolio.portfolio-index');
    }
    public function portfolio_category_data_settings(Request $request)
    {
        $only_path = 'assets/tenant/page-layout/portfolio/portfolio-category.json';
        if (!file_exists($only_path) && !is_dir($only_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/portfolio/portfolio-category.json');
        $all_data_decoded = json_decode($dynamic_pages);
        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'portfolio.category-data',compact('all_data_decoded','default_lang'));
    }
    public function update_portfolio_category_data_settings(Request $request)
    {
        $request->validate(['title' => 'required']);

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/portfolio/portfolio-category.json');
        $only_path = 'assets/tenant/page-layout/portfolio/portfolio-category.json';
        $all_data_decoded = json_decode($dynamic_pages) ?? [];

        $id = $request->id;
        $title = $request->title;
        $lang = $request->lang;

        foreach ($all_data_decoded->data ?? [] as $item) {
            if($item->id == $id){
                //title
                $title_decoded = (array) json_decode($item->title) ?? [];
                $title_decoded[$lang] = $title;
                $item->title = json_encode($title_decoded);
            }
        }

        file_put_contents($only_path ,json_encode($all_data_decoded));

        return redirect()->back()->with(FlashMsg::item_update());
    }
    public function portfolio_data_settings(Request $request)
    {
        $only_path = 'assets/tenant/page-layout/portfolio/portfolio.json';
        if (!file_exists($only_path) && !is_dir($only_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }
        $dynamic_pages = file_get_contents('assets/tenant/page-layout/portfolio/portfolio.json');
        $all_data_decoded = json_decode($dynamic_pages);
        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'portfolio.portfolio-data',compact('all_data_decoded','default_lang'));
    }
    public function update_portfolio_data_settings(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'client' => 'required',
            'design' => 'required',
            'typography' => 'required',
            'description' => 'required',
        ]);

        $path = file_get_contents('assets/tenant/page-layout/portfolio/portfolio.json');
        $only_path = 'assets/tenant/page-layout/portfolio/portfolio.json';
        $all_data_decoded = json_decode($path);

        $id = $request->id;
        $title = $request->title;
        $client = $request->client;
        $design = $request->design;
        $typography = purify_html($request->typography);
        $description = purify_html($request->description);
        $lang = $request->lang;


        foreach ($all_data_decoded->data ?? [] as $item) {
            if($item->id == $id){

                //title
                $decoded_title = (array) json_decode($item->title) ?? [];
                $decoded_title[$lang] = $title;
                $item->title = json_encode($decoded_title);

                //$client
                $decoded_client = (array) json_decode($item->client) ?? [];
                $decoded_client[$lang] = $client;
                $item->client = json_encode($decoded_client);

                //$design
                $decoded_design = (array) json_decode($item->design) ?? [];
                $decoded_design[$lang] = $design;
                $item->design = json_encode($decoded_design);

                //$typography
                $decoded_typography = (array) json_decode($item->typography) ?? [];
                $decoded_typography[$lang] = $typography;
                $item->typography = json_encode($decoded_typography);

                //description
                $decoded_description = (array) json_decode(get_data_without_extra_space_or_new_line($item->description)) ?? [];
                $decoded_description[$lang] = $description;
                $item->description = json_encode($decoded_description);
            }
        }

        file_put_contents($only_path ,json_encode($all_data_decoded));

        return redirect()->back()->with(FlashMsg::item_update());
    }
//Portfolio Data

//Pages Data
    public function pages_index()
    {
        return view(self::BASE_PATH.'pages.page-index');
    }
    public function pages_data_settings(Request $request)
    {
        $only_path = 'assets/tenant/page-layout/dynamic-pages.json';
        if (!file_exists($only_path) && !is_dir($only_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/dynamic-pages.json');
        $all_data_decoded = json_decode($dynamic_pages);
        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'pages.page-data',compact('all_data_decoded','default_lang'));
    }
    public function update_pages_data_settings(Request $request)
    {
        $request->validate([
            'title' => 'required',
        ]);

        $path = file_get_contents('assets/tenant/page-layout/dynamic-pages.json');
        $only_path = 'assets/tenant/page-layout/dynamic-pages.json';
        $all_data_decoded = json_decode($path);

        $id = $request->id;
        $title = $request->title;
        $lang = $request->lang;


        foreach ($all_data_decoded->data ?? [] as $item) {
            if($item->id == $id){

                //title
                $decoded_title = (array) json_decode($item->title) ?? [];
                $decoded_title[$lang] = $title;
                $item->title = json_encode($decoded_title);

            }
        }

        file_put_contents($only_path ,json_encode($all_data_decoded));

        return redirect()->back()->with(FlashMsg::item_update());
    }
//Pages Data


//Blog Data
    public function blog_index()
    {
        return view(self::BASE_PATH.'blog.blog-index');
    }
    public function blog_category_data_settings(Request $request)
    {
        $only_path = 'assets/tenant/page-layout/blog/blog-category.json';
        if (!file_exists($only_path) && !is_dir($only_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/blog/blog-category.json');
        $all_data_decoded = json_decode($dynamic_pages);
        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'blog.category-data',compact('all_data_decoded','default_lang'));
    }
    public function update_blog_category_data_settings(Request $request)
    {
        $request->validate(['title' => 'required']);

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/blog/blog-category.json');
        $only_path = 'assets/tenant/page-layout/blog/blog-category.json';
        $all_data_decoded = json_decode($dynamic_pages);

        $id = $request->id;
        $title = $request->title;
        $lang = $request->lang;

        foreach ($all_data_decoded->data ?? [] as $item) {
            if($item->id == $id){
                //title
                $title_decoded = (array) json_decode($item->title) ?? [];
                $title_decoded[$lang] = $title;
                $item->title = json_encode($title_decoded);
            }
        }

        file_put_contents($only_path ,json_encode($all_data_decoded));

        return redirect()->back()->with(FlashMsg::item_update());
    }
    public function blog_data_settings(Request $request)
    {
        $only_path = 'assets/tenant/page-layout/blog/blog.json';
        if (!file_exists($only_path) && !is_dir($only_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/blog/blog.json');
        $all_data_decoded = json_decode($dynamic_pages);
        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'blog.blog-data',compact('all_data_decoded','default_lang'));
    }
    public function update_blog_data_settings(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        $path = file_get_contents('assets/tenant/page-layout/blog/blog.json');
        $only_path = 'assets/tenant/page-layout/blog/blog.json';

        $all_data_decoded = json_decode($path) ?? [];

        $id = $request->id;
        $title = $request->title;
        $description =  purify_html($request->description);
        $lang = $request->lang;


        foreach ($all_data_decoded->data ?? [] as $item) {
            if($item->id == $id){

                //title
                $decoded_title = (array) json_decode($item->title) ?? [];
                $decoded_title[$lang] = $title;
                $item->title = json_encode($decoded_title);

                //description
                $decoded_description = (array) json_decode(get_data_without_extra_space_or_new_line($item->blog_content)) ?? [];
                $decoded_description[$lang] = $description;
                $item->blog_content = json_encode($decoded_description);
            }
        }

        file_put_contents($only_path ,json_encode($all_data_decoded));

        return redirect()->back()->with(FlashMsg::item_update());
    }
//Blog Data


//Service Data
    public function service_index()
    {
        return view(self::BASE_PATH.'service.service-index');
    }
    public function service_category_data_settings(Request $request)
    {
        $only_path ='assets/tenant/page-layout/service/service-category.json';
        if (!file_exists($only_path) && !is_dir($only_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/service/service-category.json');
        $all_data_decoded = json_decode($dynamic_pages);
        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'service.category-data',compact('all_data_decoded','default_lang'));
    }
    public function update_service_category_data_settings(Request $request)
    {
        $request->validate(['title' => 'required']);

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/service/service-category.json');
        $only_path = 'assets/tenant/page-layout/service/service-category.json';

        $all_data_decoded = json_decode($dynamic_pages);

        $id = $request->id;
        $title = $request->title;
        $lang = $request->lang;

        foreach ($all_data_decoded->data ?? [] as $item) {
            if($item->id == $id){
                //title
                $title_decoded = (array) json_decode($item->title) ?? [];
                $title_decoded[$lang] = $title;
                $item->title = json_encode($title_decoded);
            }
        }

        file_put_contents($only_path ,json_encode($all_data_decoded));

        return redirect()->back()->with(FlashMsg::item_update());
    }
    public function service_data_settings(Request $request)
    {
        $only_path = 'assets/tenant/page-layout/service/service.json';
        if (!file_exists($only_path) && !is_dir($only_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/service/service.json');
        $all_data_decoded = json_decode($dynamic_pages);
        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'service.service-data',compact('all_data_decoded','default_lang'));
    }
    public function update_service_data_settings(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        $path = file_get_contents('assets/tenant/page-layout/service/service.json');
        $only_path = 'assets/tenant/page-layout/service/service.json';

        $all_data_decoded = json_decode($path) ?? [];

        $id = $request->id;
        $title = $request->title;
        $description = purify_html($request->description);
        $lang = $request->lang;


        foreach ($all_data_decoded->data ?? [] as $item) {
            if($item->id == $id){

                //title
                $decoded_title = (array) json_decode($item->title) ?? [];
                $decoded_title[$lang] = $title;
                $item->title = json_encode($decoded_title);

                //description
                $decoded_description = (array) json_decode(get_data_without_extra_space_or_new_line($item->description)) ?? [];
                $decoded_description[$lang] = $description;
                $item->description = json_encode($decoded_description);
            }
        }

        file_put_contents($only_path ,json_encode($all_data_decoded));

        return redirect()->back()->with(FlashMsg::item_update());
    }
//Service Data


//Article Data
    public function article_index()
    {
        return view(self::BASE_PATH.'article.article-index');
    }
    public function article_category_data_settings(Request $request)
    {
        $only_path = 'assets/tenant/page-layout/article/article-category.json';
        if (!file_exists($only_path) && !is_dir($only_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/article/article-category.json');
        $all_data_decoded = json_decode($dynamic_pages);
        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'article.category-data',compact('all_data_decoded','default_lang'));
    }
    public function update_article_category_data_settings(Request $request)
    {
        $request->validate(['title' => 'required']);

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/article/article-category.json');
        $only_path = 'assets/tenant/page-layout/article/article-category.json';

        $all_data_decoded = json_decode($dynamic_pages);

        $id = $request->id;
        $title = $request->title;
        $lang = $request->lang;

        foreach ($all_data_decoded->data ?? [] as $item) {
            if($item->id == $id){
                //title
                $title_decoded = (array) json_decode($item->title) ?? [];
                $title_decoded[$lang] = $title;
                $item->title = json_encode($title_decoded);
            }
        }

        file_put_contents($only_path ,json_encode($all_data_decoded));

        return redirect()->back()->with(FlashMsg::item_update());
    }
    public function article_data_settings(Request $request)
    {
        $only_path = 'assets/tenant/page-layout/article/article.json';
        if (!file_exists($only_path) && !is_dir($only_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/article/article.json');
        $all_data_decoded = json_decode($dynamic_pages);
        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'article.article-data',compact('all_data_decoded','default_lang'));
    }
    public function update_article_data_settings(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        $path = file_get_contents('assets/tenant/page-layout/article/article.json');
        $only_path = 'assets/tenant/page-layout/article/article.json';

        $all_data_decoded = json_decode($path) ?? [];

        $id = $request->id;
        $title = $request->title;
        $description = purify_html($request->description);
        $lang = $request->lang;


        foreach ($all_data_decoded->data ?? [] as $item) {
            if($item->id == $id){

                //title
                $decoded_title = (array) json_decode($item->title) ?? [];
                $decoded_title[$lang] = $title;
                $item->title = json_encode($decoded_title);

                //description
                $decoded_description = (array) json_decode(get_data_without_extra_space_or_new_line($item->description)) ?? [];
                $decoded_description[$lang] = $description;
                $item->description = json_encode($decoded_description);
            }
        }

        file_put_contents($only_path ,json_encode($all_data_decoded));

        return redirect()->back()->with(FlashMsg::item_update());
    }
//Article Data


//Testimonial Data
    public function testimonial_index()
    {
        return view(self::BASE_PATH.'testimonial.testimonial-index');
    }
    public function testimonial_data_settings(Request $request)
    {
        $only_path = 'assets/tenant/page-layout/testimonial.json';
        if (!file_exists($only_path) && !is_dir($only_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/testimonial.json');
        $all_data_decoded = json_decode($dynamic_pages);
        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'testimonial.testimonial-data',compact('all_data_decoded','default_lang'));
    }
    public function update_testimonial_data_settings(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'designation' => 'required',
            'company' => 'required',
            'description' => 'required',
        ]);

        $path = file_get_contents('assets/tenant/page-layout/testimonial.json');
        $only_path = 'assets/tenant/page-layout/testimonial.json';

        $all_data_decoded = json_decode($path);

        $id = $request->id;
        $name = $request->name;
        $designation = $request->designation;
        $company = $request->company;
        $description = purify_html($request->description);
        $lang = $request->lang;


        foreach ($all_data_decoded->data ?? [] as $item) {
            if($item->id == $id){

                //name
                $decoded_name = (array) json_decode($item->name) ?? [];
                $decoded_name[$lang] = $name;
                $item->name = json_encode($decoded_name);

                //designation
                $designation_name = (array) json_decode($item->designation) ?? [];
                $designation_name[$lang] = $designation;
                $item->designation = json_encode($designation_name);

                //company
                $company_name = (array) json_decode($item->company) ?? [];
                $company_name[$lang] = $company;
                $item->company = json_encode($company_name);

                //description
                $description_name = (array) json_decode($item->description) ?? [];
                $description_name[$lang] = $description;
                $item->description = json_encode($description_name);

            }
        }

        file_put_contents($only_path ,json_encode($all_data_decoded));

        return redirect()->back()->with(FlashMsg::item_update());
    }
//Testimonial Data



//FAQ Data
    public function faq_index()
    {
        return view(self::BASE_PATH.'faq.faq-index');
    }
    public function faq_category_data_settings(Request $request)
    {
        $only_path = 'assets/tenant/page-layout/faq/faq-category.json';
        if (!file_exists($only_path) && !is_dir($only_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/faq/faq-category.json');
        $all_data_decoded = json_decode($dynamic_pages);
        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'faq.category-data',compact('all_data_decoded','default_lang'));
    }
    public function update_faq_category_data_settings(Request $request)
    {
        $request->validate(['title' => 'required']);

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/faq/faq-category.json');
        $only_path = 'assets/tenant/page-layout/faq/faq-category.json';

        $all_data_decoded = json_decode($dynamic_pages);

        $id = $request->id;
        $title = $request->title;
        $lang = $request->lang;

        foreach ($all_data_decoded->data ?? [] as $item) {
            if($item->id == $id){
                //title
                $title_decoded = (array) json_decode($item->title) ?? [];
                $title_decoded[$lang] = $title;
                $item->title = json_encode($title_decoded);
            }
        }

        file_put_contents($only_path ,json_encode($all_data_decoded));

        return redirect()->back()->with(FlashMsg::item_update());
    }
    public function faq_data_settings(Request $request)
    {
        $only_path = 'assets/tenant/page-layout/faq/faq.json';
        if (!file_exists($only_path) && !is_dir($only_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/faq/faq.json');
        $all_data_decoded = json_decode($dynamic_pages);
        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'faq.faq-data',compact('all_data_decoded','default_lang'));
    }
    public function update_faq_data_settings(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        $path = file_get_contents('assets/tenant/page-layout/faq/faq.json');
        $only_path = 'assets/tenant/page-layout/faq/faq.json';

        $all_data_decoded = json_decode($path) ?? [];

        $id = $request->id;
        $title = $request->title;
        $description = purify_html($request->description);
        $lang = $request->lang;


        foreach ($all_data_decoded->data ?? [] as $item) {
            if($item->id == $id){

                //title
                $decoded_title = (array) json_decode($item->title) ?? [];
                $decoded_title[$lang] = $title;
                $item->title = json_encode($decoded_title);

                //description
                $decoded_description = (array) json_decode(get_data_without_extra_space_or_new_line($item->description)) ?? [];
                $decoded_description[$lang] = $description;
                $item->description = json_encode($decoded_description);
            }
        }

        file_put_contents($only_path ,json_encode($all_data_decoded));

        return redirect()->back()->with(FlashMsg::item_update());
    }
//FAQ Data


//Price Plan Data
    public function price_plan_index()
    {
        return view(self::BASE_PATH.'price-plan.price-plan-index');
    }
    public function price_plan_data_settings(Request $request)
    {
        $only_path = 'assets/tenant/page-layout/price-plan.json';
        if (!file_exists($only_path) && !is_dir($only_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/price-plan.json');
        $all_data_decoded = json_decode($dynamic_pages);
        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'price-plan.price-plan-data',compact('all_data_decoded','default_lang'));
    }
    public function update_price_plan_data_settings(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'features' => 'required',
            'not_available_features' => 'required',
        ]);

        $path = file_get_contents('assets/tenant/page-layout/price-plan.json');
        $only_path = 'assets/tenant/page-layout/price-plan.json';
        $all_data_decoded = json_decode($path);

        $id = $request->id;
        $title = $request->title;
        $features = purify_html($request->features);
        $not_available_features = purify_html($request->not_available_features);
        $lang = $request->lang;


        foreach ($all_data_decoded->data ?? [] as $item) {
            if($item->id == $id){

                //Title
                $decoded_title = (array) json_decode($item->title) ?? [];
                $decoded_title[$lang] = $title;
                $item->title = json_encode($decoded_title);

                //Features
                $features_name = (array) json_decode($item->features) ?? [];
                $features_name[$lang] = $features;
                $item->features = json_encode($features_name);

                //Not available features
                $not_features_name = (array) json_decode($item->not_available_features) ?? [];
                $not_features_name[$lang] = $not_available_features;
                $item->not_available_features = json_encode($not_features_name);

            }
        }

        file_put_contents($only_path ,json_encode($all_data_decoded));

        return redirect()->back()->with(FlashMsg::item_update());
    }
//Price Plan Data


//Image Gallery Data
    public function gallery_index()
    {
        return view(self::BASE_PATH.'gallery.gallery-index');
    }
    public function gallery_category_data_settings(Request $request)
    {
        $only_path = 'assets/tenant/page-layout/gallery/gallery-category.json';
        if (!file_exists($only_path) && !is_dir($only_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/gallery/gallery-category.json');
        $all_data_decoded = json_decode($dynamic_pages);
        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'gallery.category-data',compact('all_data_decoded','default_lang'));
    }
    public function update_gallery_category_data_settings(Request $request)
    {
        $request->validate(['title' => 'required']);

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/gallery/gallery-category.json');
        $only_path = 'assets/tenant/page-layout/gallery/gallery-category.json';

        $all_data_decoded = json_decode($dynamic_pages);

        $id = $request->id;
        $title = $request->title;
        $lang = $request->lang;

        foreach ($all_data_decoded->data ?? [] as $item) {
            if($item->id == $id){
                //title
                $title_decoded = (array) json_decode($item->title) ?? [];
                $title_decoded[$lang] = $title;
                $item->title = json_encode($title_decoded);
            }
        }

        file_put_contents($only_path ,json_encode($all_data_decoded));

        return redirect()->back()->with(FlashMsg::item_update());
    }
    public function gallery_data_settings(Request $request)
    {
        $only_path = 'assets/tenant/page-layout/gallery/gallery.json';
        if (!file_exists($only_path) && !is_dir($only_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/gallery/gallery.json');
        $all_data_decoded = json_decode($dynamic_pages);
        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'gallery.gallery-data',compact('all_data_decoded','default_lang'));
    }
    public function update_gallery_data_settings(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'subtitle' => 'required',
        ]);

        $path = file_get_contents('assets/tenant/page-layout/gallery/gallery.json');
        $only_path = 'assets/tenant/page-layout/gallery/gallery.json';

        $all_data_decoded = json_decode($path) ?? [];

        $id = $request->id;
        $title = $request->title;
        $subtitle = $request->subtitle;
        $lang = $request->lang;


        foreach ($all_data_decoded->data ?? [] as $item) {
            if($item->id == $id){

                //title
                $decoded_title = (array) json_decode($item->title) ?? [];
                $decoded_title[$lang] = $title;
                $item->title = json_encode($decoded_title);

                //description
                $decoded_subtitle = (array) json_decode(get_data_without_extra_space_or_new_line($item->subtitle)) ?? [];
                $decoded_subtitle[$lang] = $subtitle;
                $item->subtitle = json_encode($decoded_subtitle);
            }
        }

        file_put_contents($only_path ,json_encode($all_data_decoded));

        return redirect()->back()->with(FlashMsg::item_update());
    }
//Image Gallery Data


//Appointment Data

    public function appointment_index()
    {
        return view(self::BASE_PATH.'appointment.appointment-index');
    }
    public function appointment_category_data_settings(Request $request)
    {
        $only_path = 'assets/tenant/page-layout/appointment/category.json';
        if (!file_exists($only_path) && !is_dir($only_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/appointment/category.json');
        $all_data_decoded = json_decode($dynamic_pages);

        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'appointment.category-data',compact('all_data_decoded','default_lang'));
    }
    public function update_appointment_category_data_settings(Request $request)
    {
        $request->validate(['title' => 'required']);

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/appointment/category.json');
        $only_path = 'assets/tenant/page-layout/appointment/category.json';

        $all_data_decoded = json_decode($dynamic_pages);

        $id = $request->id;
        $title = $request->title;
        $lang = $request->lang;

        foreach ($all_data_decoded->data ?? [] as $item) {
            if($item->id == $id){
                $decoded = (array) json_decode($item->title) ?? [];
                $decoded[$lang] = $title;
                $item->title = json_encode($decoded);
            }
        }

        file_put_contents($only_path ,json_encode($all_data_decoded));

        return redirect()->back()->with(FlashMsg::item_update());
    }

    public function appointment_sub_category_data_settings(Request $request)
    {
        $only_path = 'assets/tenant/page-layout/appointment/sub-category.json';
        if (!file_exists($only_path) && !is_dir($only_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/appointment/sub-category.json');
        $all_data_decoded = json_decode($dynamic_pages);

        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'appointment.sub-category-data',compact('all_data_decoded','default_lang'));
    }
    public function update_appointment_sub_category_data_settings(Request $request)
    {
        $request->validate(['title' => 'required']);

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/appointment/sub-category.json');
        $only_path = 'assets/tenant/page-layout/appointment/sub-category.json';

        $all_data_decoded = json_decode($dynamic_pages);

        $id = $request->id;
        $title = $request->title;
        $lang = $request->lang;

        foreach ($all_data_decoded->data ?? [] as $item) {
            if($item->id == $id){
                $decoded = (array) json_decode($item->title) ?? [];
                $decoded[$lang] = $title;
                $item->title = json_encode($decoded);
            }
        }

        file_put_contents($only_path ,json_encode($all_data_decoded));

        return redirect()->back()->with(FlashMsg::item_update());
    }

    public function sub_appointment_data_settings(Request $request)
    {
        $path = 'assets/tenant/page-layout/appointment/sub-appointment.json';
        if (!file_exists($path) && !is_dir($path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/appointment/sub-appointment.json');
        $all_data_decoded = json_decode($dynamic_pages);
        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'appointment.sub-appointment-data',compact('all_data_decoded','default_lang'));
    }
    public function update_sub_appointment_data_settings(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/appointment/sub-appointment.json');
        $only_path = 'assets/tenant/page-layout/appointment/sub-appointment.json';

        $all_data_decoded = json_decode($dynamic_pages);

        $id = $request->id;
        $title = $request->title;
        $description = purify_html($request->description);
        $lang = $request->lang;


        foreach ($all_data_decoded->data ?? [] as $item) {
            if($item->id == $id){
                //title
                $decoded_title = (array) json_decode($item->title) ?? [];
                $decoded_title[$lang] = $title;
                $item->title = json_encode($decoded_title);

                //description
                $decoded_description = (array) json_decode($item->description) ?? [];
                $decoded_description[$lang] = $description;
                $item->description = json_encode($decoded_description);
            }
        }

        file_put_contents($only_path ,json_encode($all_data_decoded));

        return redirect()->back()->with(FlashMsg::item_update());
    }

    public function appointment_data_settings(Request $request)
    {
        $path = 'assets/tenant/page-layout/appointment/appointment.json';
        if (!file_exists($path) && !is_dir($path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/appointment/appointment.json');
        $all_data_decoded = json_decode($dynamic_pages);
        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'appointment.appointment-data',compact('all_data_decoded','default_lang'));
    }
    public function update_appointment_data_settings(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/appointment/appointment.json');
        $only_path = 'assets/tenant/page-layout/appointment/appointment.json';

        $all_data_decoded = json_decode($dynamic_pages);

        $id = $request->id;
        $title = $request->title;
        $description = purify_html($request->description);
        $lang = $request->lang;


        foreach ($all_data_decoded->data ?? [] as $item) {
            if($item->id == $id){
                //title
                $decoded_title = (array) json_decode($item->title) ?? [];
                $decoded_title[$lang] = $title;
                $item->title = json_encode($decoded_title);

                //description
                $decoded_description = (array) json_decode($item->description) ?? [];
                $decoded_description[$lang] = $description;
                $item->description = json_encode($decoded_description);
            }
        }

        file_put_contents($only_path ,json_encode($all_data_decoded));

        return redirect()->back()->with(FlashMsg::item_update());
    }
//Appointment Data




}
