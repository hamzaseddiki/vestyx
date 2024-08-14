<?php

namespace App\Traits\SeederMethods;

use App\Helpers\FlashMsg;
use Illuminate\Http\Request;

trait ProductSeederMethod
{

    public function product_index()
    {
        return view(self::BASE_PATH.'product.product-index');
    }
    public function product_category_data_settings(Request $request)
    {
        $only_path ='assets/tenant/page-layout/product/product-category.json';
        if (!file_exists($only_path) && !is_dir($only_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/product/product-category.json');
        $all_data_decoded = json_decode($dynamic_pages);
        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'product.category-data',compact('all_data_decoded','default_lang'));
    }
    public function update_product_category_data_settings(Request $request)
    {
        $request->validate(['name' => 'required']);

        $path = file_get_contents('assets/tenant/page-layout/product/product-category.json');
        $only_path = 'assets/tenant/page-layout/product/product-category.json';

        $all_data_decoded = json_decode($path);

        $id = $request->id;
        $name = $request->name;
        $lang = $request->lang;

        foreach ($all_data_decoded->data ?? [] as $item) {
            if($item->id == $id){
                $name_decoded = (array) json_decode($item->name) ?? [];
                $name_decoded[$lang] = $name;
                $item->name = json_encode($name_decoded);
            }
        }

        file_put_contents($only_path ,json_encode($all_data_decoded));

        return redirect()->back()->with(FlashMsg::item_update());
    }

    public function product_subcategory_data_settings(Request $request)
    {
        $only_path ='assets/tenant/page-layout/product/product-subcategory.json';
        if (!file_exists($only_path) && !is_dir($only_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/product/product-subcategory.json');
        $all_data_decoded = json_decode($dynamic_pages);
        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'product.subcategory-data',compact('all_data_decoded','default_lang'));
    }
    public function update_product_subcategory_data_settings(Request $request)
    {
        $request->validate(['name' => 'required']);

        $path = file_get_contents('assets/tenant/page-layout/product/product-subcategory.json');
        $only_path = 'assets/tenant/page-layout/product/product-subcategory.json';

        $all_data_decoded = json_decode($path);

        $id = $request->id;
        $name = $request->name;
        $description = purify_html($request->description);
        $lang = $request->lang;

        foreach ($all_data_decoded->data ?? [] as $item) {
            if($item->id == $id){
                //name
                $name_decoded = (array) json_decode($item->name) ?? [];
                $name_decoded[$lang] = $name;
                $item->name = json_encode($name_decoded);

                //description
                $description_decoded = (array) json_decode($item->description) ?? [];
                $description_decoded[$lang] = $description;
                $item->description = json_encode($description_decoded);
            }
        }

        file_put_contents($only_path ,json_encode($all_data_decoded));

        return redirect()->back()->with(FlashMsg::item_update());
    }

    public function product_childcategory_data_settings(Request $request)
    {
        $only_path ='assets/tenant/page-layout/product/product-childcategory.json';
        if (!file_exists($only_path) && !is_dir($only_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/product/product-childcategory.json');
        $all_data_decoded = json_decode($dynamic_pages);
        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'product.child-category-data',compact('all_data_decoded','default_lang'));
    }
    public function update_product_childcategory_data_settings(Request $request)
    {
        $request->validate(['name' => 'required']);

        $path = file_get_contents('assets/tenant/page-layout/product/product-childcategory.json');
        $only_path = 'assets/tenant/page-layout/product/product-childcategory.json';

        $all_data_decoded = json_decode($path);

        $id = $request->id;
        $name = $request->name;
        $lang = $request->lang;

        foreach ($all_data_decoded->data ?? [] as $item) {
            if($item->id == $id){
                $name_decoded = (array) json_decode($item->name) ?? [];
                $name_decoded[$lang] = $name;
                $item->name = json_encode($name_decoded);
            }
        }

        file_put_contents($only_path ,json_encode($all_data_decoded));

        return redirect()->back()->with(FlashMsg::item_update());
    }

    public function product_color_data_settings(Request $request)
    {
        $only_path ='assets/tenant/page-layout/product/colors.json';
        if (!file_exists($only_path) && !is_dir($only_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/product/colors.json');
        $all_data_decoded = json_decode($dynamic_pages);
        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'product.color-data',compact('all_data_decoded','default_lang'));
    }
    public function update_product_color_data_settings(Request $request)
    {
        $request->validate(['name' => 'required']);

        $path = file_get_contents('assets/tenant/page-layout/product/colors.json');
        $only_path = 'assets/tenant/page-layout/product/colors.json';

        $all_data_decoded = json_decode($path);

        $id = $request->id;
        $name = $request->name;
        $lang = $request->lang;

        foreach ($all_data_decoded->data ?? [] as $item) {
            if($item->id == $id){
                $name_decoded = (array) json_decode($item->name) ?? [];
                $name_decoded[$lang] = $name;
                $item->name = json_encode($name_decoded);
            }
        }

        file_put_contents($only_path ,json_encode($all_data_decoded));

        return redirect()->back()->with(FlashMsg::item_update());
    }

    public function product_size_data_settings(Request $request)
    {
        $only_path ='assets/tenant/page-layout/product/sizes.json';
        if (!file_exists($only_path) && !is_dir($only_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/product/sizes.json');
        $all_data_decoded = json_decode($dynamic_pages);
        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'product.size-data',compact('all_data_decoded','default_lang'));
    }
    public function update_product_size_data_settings(Request $request)
    {
        $request->validate(['name' => 'required']);

        $path = file_get_contents('assets/tenant/page-layout/product/sizes.json');
        $only_path = 'assets/tenant/page-layout/product/sizes.json';

        $all_data_decoded = json_decode($path);

        $id = $request->id;
        $name = $request->name;
        $lang = $request->lang;

        foreach ($all_data_decoded->data ?? [] as $item) {
            if($item->id == $id){
                $name_decoded = (array) json_decode($item->name) ?? [];
                $name_decoded[$lang] = $name;
                $item->name = json_encode($name_decoded);
            }
        }

        file_put_contents($only_path ,json_encode($all_data_decoded));

        return redirect()->back()->with(FlashMsg::item_update());
    }

    public function product_delivery_option_data_settings(Request $request)
    {
        $only_path ='assets/tenant/page-layout/product/delivery-option.json';
        if (!file_exists($only_path) && !is_dir($only_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/product/delivery-option.json');
        $all_data_decoded = json_decode($dynamic_pages);
        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'product.delivery-option-data',compact('all_data_decoded','default_lang'));
    }
    public function update_product_delivery_option_data_settings(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'sub_title' => 'required',
        ]);

        $path = file_get_contents('assets/tenant/page-layout/product/delivery-option.json');
        $only_path = 'assets/tenant/page-layout/product/delivery-option.json';

        $all_data_decoded = json_decode($path);

        $id = $request->id;
        $title = $request->title;
        $subtitle = purify_html($request->sub_title);
        $lang = $request->lang;

        foreach ($all_data_decoded->data ?? [] as $item) {
            if($item->id == $id){
                $title_decoded = (array) json_decode($item->title) ?? [];
                $title_decoded[$lang] = $title;
                $item->title = json_encode($title_decoded);

                $sub_title_decoded = (array) json_decode($item->sub_title) ?? [];
                $sub_title_decoded[$lang] = $subtitle;
                $item->sub_title = json_encode($sub_title_decoded);
            }
        }

        file_put_contents($only_path ,json_encode($all_data_decoded));

        return redirect()->back()->with(FlashMsg::item_update());
    }

    public function product_badge_data_settings(Request $request)
    {
        $only_path ='assets/tenant/page-layout/product/badge.json';
        if (!file_exists($only_path) && !is_dir($only_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/product/badge.json');
        $all_data_decoded = json_decode($dynamic_pages);
        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'product.badge-data',compact('all_data_decoded','default_lang'));
    }
    public function update_product_badge_data_settings(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        $path = file_get_contents('assets/tenant/page-layout/product/badge.json');
        $only_path = 'assets/tenant/page-layout/product/badge.json';

        $all_data_decoded = json_decode($path);

        $id = $request->id;
        $name = $request->name;
        $lang = $request->lang;

        foreach ($all_data_decoded->data ?? [] as $item) {
            if($item->id == $id){
                $name_decoded = (array) json_decode($item->name) ?? [];
                $name_decoded[$lang] = $name;
                $item->name = json_encode($name_decoded);
            }
        }

        file_put_contents($only_path ,json_encode($all_data_decoded));

        return redirect()->back()->with(FlashMsg::item_update());
    }

    public function product_campaign_data_settings(Request $request)
    {
        $only_path ='assets/tenant/page-layout/product/campaign.json';
        if (!file_exists($only_path) && !is_dir($only_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/product/campaign.json');
        $all_data_decoded = json_decode($dynamic_pages);
        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'product.campaign-data',compact('all_data_decoded','default_lang'));
    }
    public function update_product_campaign_data_settings(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'subtitle' => 'required',
        ]);

        $path = file_get_contents('assets/tenant/page-layout/product/campaign.json');
        $only_path = 'assets/tenant/page-layout/product/campaign.json';

        $all_data_decoded = json_decode($path);

        $id = $request->id;
        $title = $request->title;
        $subtitle = purify_html($request->subtitle);
        $lang = $request->lang;

        foreach ($all_data_decoded->data ?? [] as $item) {
            if($item->id == $id){
                //title
                $title_decoded = (array) json_decode($item->title) ?? [];
                $title_decoded[$lang] = $title;
                $item->title = json_encode($title_decoded);

                //subtitle
                $subtitle_decoded = (array) json_decode($item->subtitle) ?? [];
                $subtitle_decoded[$lang] = $subtitle;
                $item->subtitle = json_encode($subtitle_decoded);
            }
        }

        file_put_contents($only_path ,json_encode($all_data_decoded));

        return redirect()->back()->with(FlashMsg::item_update());
    }

    public function product_return_policy_data_settings(Request $request)
    {
        $only_path ='assets/tenant/page-layout/product/return-policy.json';
        if (!file_exists($only_path) && !is_dir($only_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/product/return-policy.json');
        $all_data_decoded = json_decode($dynamic_pages);
        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'product.return-policy-data',compact('all_data_decoded','default_lang'));
    }
    public function update_product_return_policy_data_settings(Request $request)
    {
        $request->validate([
            'description' => 'required',
        ]);

        $path = file_get_contents('assets/tenant/page-layout/product/return-policy.json');
        $only_path = 'assets/tenant/page-layout/product/return-policy.json';

        $all_data_decoded = json_decode($path);

        $id = $request->id;
        $description = purify_html($request->description);
        $lang = $request->lang;

        foreach ($all_data_decoded->data ?? [] as $item) {
            if($item->id == $id){

                //description
                $description_decoded = (array) json_decode($item->shipping_return_description) ?? [];
                $description_decoded[$lang] = $description;
                $item->shipping_return_description = json_encode($description_decoded);
            }
        }

        file_put_contents($only_path ,json_encode($all_data_decoded));

        return redirect()->back()->with(FlashMsg::item_update());
    }

    //Products
    public function product_data_settings(Request $request)
    {
        $only_path ='assets/tenant/page-layout/product/product.json';
        if (!file_exists($only_path) && !is_dir($only_path)){
            return redirect()->back()->with(['msg' => __('Seeder file not found'),'type' => 'danger']);
        }

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/product/product.json');
        $all_data_decoded = json_decode($dynamic_pages);
        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH.'product.product-data',compact('all_data_decoded','default_lang'));
    }
    public function update_product_data_settings(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'summary' => 'required',
            'description' => 'required',
        ]);

        $path = file_get_contents('assets/tenant/page-layout/product/product.json');
        $only_path = 'assets/tenant/page-layout/product/product.json';
        $all_data_decoded = json_decode($path);

        $id = $request->id;
        $name = $request->name;
        $summary = purify_html($request->summary);
        $description = purify_html($request->description);
        $lang = $request->lang;

        foreach ($all_data_decoded->data ?? [] as $item) {
            if($item->id == $id){
                //name
                $name_decoded = (array) json_decode($item->name) ?? [];
                $name_decoded[$lang] = $name;
                $item->name = json_encode($name_decoded);

                //summary
                $summary_decoded = (array) json_decode($item->summary) ?? [];
                $summary_decoded[$lang] = $summary;
                $item->summary = json_encode($summary_decoded);

                //description
                $description_decoded = (array) json_decode($item->description) ?? [];
                $description_decoded[$lang] = $description;
                $item->description = json_encode($description_decoded);
            }
        }

        file_put_contents($only_path ,json_encode($all_data_decoded));
        return redirect()->back()->with(FlashMsg::item_update());
    }
}
