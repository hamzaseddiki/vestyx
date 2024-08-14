<?php

namespace App\Traits\SeederMethods;

use App\Helpers\FlashMsg;
use App\Helpers\SeederHelpers\JsonDataModifier;
use App\Models\Language;
use Illuminate\Http\Request;

trait LanguageSeederMethod
{

    public function language_data_settings(Request $request)
    {
        $only_path = 'assets/tenant/page-layout/language.json';
        if (!file_exists($only_path) && !is_dir($only_path)) {
            return redirect()->back()->with(['msg' => __('Seeder file not found'), 'type' => 'danger']);
        }

        $dynamic_pages = file_get_contents('assets/tenant/page-layout/language.json');
        $all_data_decoded = json_decode($dynamic_pages);
        $default_lang = $request->lang ?? default_lang();

        return view(self::BASE_PATH . 'language.language-index', compact('all_data_decoded', 'default_lang'));
    }


    public function make_default_language_data_settings($id)
    {
        $only_path = file_get_contents('assets/tenant/page-layout/language.json');
        $all_languages = json_decode($only_path);

        foreach ($all_languages?->data as $lang) {
            $lang->default = 0;
            if ($lang->id == $id) {
                $lang->default = 1;
            }
        }

        file_put_contents('assets/tenant/page-layout/language.json', json_encode($all_languages));

        return redirect()->back()->with([
            'msg' => __('Default Language changed'),
            'type' => 'success'
        ]);
    }

    public function update_language_data_settings(Request $request)
    {
        $this->validate($request, [
            'language_select' => 'required|string|max:191',
            'lang' => 'required|string|max:191',
            'slug' => 'required|string|max:191',
        ]);


        //todo filter if current and old language is same return back
        if ($request->language_select == $request->lang) {
            return back()->with(['msg' => __('current language and new language can not be same'), 'type' => 'warning']);
        }


        $path = file_get_contents('assets/tenant/page-layout/language.json');
        $only_path = 'assets/tenant/page-layout/language.json';

        $all_data_decoded = json_decode($path);

        foreach ($all_data_decoded->data as $language) {

            if ($language->slug === $request->lang) {
                $language->name = $request->name;
                $language->slug = $request->slug;
                $language->direction = $request->direction;

                //Article category
                $article_cat = new JsonDataModifier('article', 'article-category');
                $article_cat->replaceColumnLanguage(["title"], $request->lang, $request->slug);
                $article_cat->saveFile(true);
                //Article
                $article = new JsonDataModifier('article', 'article');
                $article->replaceColumnLanguage(["title"], $request->lang, $request->slug);
                $article->replaceColumnLanguage(["description"], $request->lang, $request->slug);
                $article->saveFile(true);

                //Blog category
                $blog_cat = new JsonDataModifier('blog', 'blog-category');
                $blog_cat->replaceColumnLanguage(["title"], $request->lang, $request->slug);
                $blog_cat->saveFile(true);
                //Blog
                $blog = new JsonDataModifier('blog', 'blog');
                $blog->replaceColumnLanguage(["title"], $request->lang, $request->slug);
                $blog->replaceColumnLanguage(["blog_content"], $request->lang, $request->slug);
                $blog->replaceColumnLanguage(["excerpt"], $request->lang, $request->slug);
                $blog->saveFile(true);

                //Donation category
                $donation_cat = new JsonDataModifier('donation', 'donation-category');
                $donation_cat->replaceColumnLanguage(["title"], $request->lang, $request->slug);
                $donation_cat->saveFile(true);
                //Donation
                $donation = new JsonDataModifier('donation', 'donation');
                $donation->replaceColumnLanguage(["title"], $request->lang, $request->slug);
                $donation->replaceColumnLanguage(["description"], $request->lang, $request->slug);
                $donation->saveFile(true);

                //Donation Activities category
                $donation_act_cat = new JsonDataModifier('donation', 'donation-activities-category');
                $donation_act_cat->replaceColumnLanguage(["title"], $request->lang, $request->slug);
                $donation_act_cat->saveFile(true);
                //Donation Activities
                $donation_act = new JsonDataModifier('donation', 'donation-activities');
                $donation_act->replaceColumnLanguage(["title"], $request->lang, $request->slug);
                $donation_act->replaceColumnLanguage(["description"], $request->lang, $request->slug);
                $donation_act->saveFile(true);

                //Event category
                $event_cat = new JsonDataModifier('event', 'event-category');
                $event_cat->replaceColumnLanguage(["title"], $request->lang, $request->slug);
                $event_cat->saveFile(true);

                //Event
                $event = new JsonDataModifier('event', 'event');
                $event->replaceColumnLanguage(["title"], $request->lang, $request->slug);
                $event->replaceColumnLanguage(["content"], $request->lang, $request->slug);
                $event->saveFile(true);

                //Faq category
                $faq_cat = new JsonDataModifier('faq', 'faq-category');
                $faq_cat->replaceColumnLanguage(["title"], $request->lang, $request->slug);
                $faq_cat->saveFile(true);
                //Faq
                $faq = new JsonDataModifier('faq', 'faq');
                $faq->replaceColumnLanguage(["title"], $request->lang, $request->slug);
                $faq->replaceColumnLanguage(["description"], $request->lang, $request->slug);
                $faq->saveFile(true);

                //Gallery category
                $gallery_cat = new JsonDataModifier('gallery', 'gallery-category');
                $gallery_cat->replaceColumnLanguage(["title"], $request->lang, $request->slug);
                $gallery_cat->saveFile(true);
                //Gallery
                $gallery = new JsonDataModifier('gallery', 'gallery');
                $gallery->replaceColumnLanguage(["title"], $request->lang, $request->slug);
                $gallery->replaceColumnLanguage(["subtitle"], $request->lang, $request->slug);
                $gallery->saveFile(true);

                //Job category
                $job_cat = new JsonDataModifier('job', 'job-category');
                $job_cat->replaceColumnLanguage(["title"], $request->lang, $request->slug);
                $job_cat->replaceColumnLanguage(["subtitle"], $request->lang, $request->slug);
                $job_cat->saveFile(true);
                //Job
                $job = new JsonDataModifier('job', 'job');
                $job->replaceColumnLanguage(["title"], $request->lang, $request->slug);
                $job->replaceColumnLanguage(["designation"], $request->lang, $request->slug);
                $job->replaceColumnLanguage(["job_location"], $request->lang, $request->slug);
                $job->replaceColumnLanguage(["description"], $request->lang, $request->slug);
                $job->replaceColumnLanguage(["company_name"], $request->lang, $request->slug);
                $job->saveFile(true);

                //Portfolio category
                $portfolio_cat = new JsonDataModifier('portfolio', 'portfolio-category');
                $portfolio_cat->replaceColumnLanguage(["title"], $request->lang, $request->slug);
                $portfolio_cat->saveFile(true);
                //Portfolio
                $portfolio = new JsonDataModifier('portfolio', 'portfolio');
                $portfolio->replaceColumnLanguage(["title"], $request->lang, $request->slug);
                $portfolio->replaceColumnLanguage(["description"], $request->lang, $request->slug);
                $portfolio->replaceColumnLanguage(["client"], $request->lang, $request->slug);
                $portfolio->replaceColumnLanguage(["design"], $request->lang, $request->slug);
                $portfolio->replaceColumnLanguage(["typography"], $request->lang, $request->slug);
                $portfolio->saveFile(true);

                // ============================= ECOMMERCE ==============================
                //Badge
                $badge = new JsonDataModifier('product', 'badge');
                $badge->replaceColumnLanguage(["name"], $request->lang, $request->slug);
                $badge->saveFile(true);

                //Campaign
                $campaign = new JsonDataModifier('product', 'campaign');
                $campaign->replaceColumnLanguage(["title"], $request->lang, $request->slug);
                $campaign->replaceColumnLanguage(["subtitle"], $request->lang, $request->slug);
                $campaign->saveFile(true);

                //Colors
                $color = new JsonDataModifier('product', 'colors');
                $color->replaceColumnLanguage(["name"], $request->lang, $request->slug);
                $color->saveFile(true);

                //Sizes
                $size = new JsonDataModifier('product', 'sizes');
                $size->replaceColumnLanguage(["name"], $request->lang, $request->slug);
                $size->saveFile(true);

                //Delivery Option
                $do = new JsonDataModifier('product', 'delivery-option');
                $do->replaceColumnLanguage(["title"], $request->lang, $request->slug);
                $do->replaceColumnLanguage(["sub_title"], $request->lang, $request->slug);
                $do->saveFile(true);

                //Products Category
                $product = new JsonDataModifier('product', 'product-category');
                $product->replaceColumnLanguage(["name"], $request->lang, $request->slug);
                $product->saveFile(true);

                //Products Subcategory
                $product_sub = new JsonDataModifier('product', 'product-subcategory');
                $product_sub->replaceColumnLanguage(["name"], $request->lang, $request->slug);
                $product_sub->replaceColumnLanguage(["description"], $request->lang, $request->slug);
                $product_sub->saveFile(true);

                //Products Childcategory
                $product_child = new JsonDataModifier('product', 'product-childcategory');
                $product_child->replaceColumnLanguage(["name"], $request->lang, $request->slug);
                $product_child->saveFile(true);

                //Products
                $product = new JsonDataModifier('product', 'product');
                $product->replaceColumnLanguage(["name"], $request->lang, $request->slug);
                $product->replaceColumnLanguage(["summary"], $request->lang, $request->slug);
                $product->replaceColumnLanguage(["description"], $request->lang, $request->slug);
                $product->saveFile(true);

                //Return policy
                $product = new JsonDataModifier('product', 'return-policy');
                $product->replaceColumnLanguage(["shipping_return_description"], $request->lang, $request->slug);
                $product->saveFile(true);

                // ============================= ECOMMERCE ==============================

                //Service category
                $service_cat = new JsonDataModifier('service', 'service-category');
                $service_cat->replaceColumnLanguage(["title"], $request->lang, $request->slug);
                $service_cat->saveFile(true);
                //Service
                $service = new JsonDataModifier('service', 'service');
                $service->replaceColumnLanguage(["title"], $request->lang, $request->slug);
                $service->replaceColumnLanguage(["description"], $request->lang, $request->slug);
                $service->saveFile(true);

                //Pages
                $page = new JsonDataModifier('', 'dynamic-pages');
                $page->replaceColumnLanguage(["title"], $request->lang, $request->slug);
                $page->saveFile(true);

                //Testimonial
                $testimonial = new JsonDataModifier('', 'testimonial');
                $testimonial->replaceColumnLanguage(["name"], $request->lang, $request->slug);
                $testimonial->replaceColumnLanguage(["designation"], $request->lang, $request->slug);
                $testimonial->replaceColumnLanguage(["company"], $request->lang, $request->slug);
                $testimonial->replaceColumnLanguage(["description"], $request->lang, $request->slug);
                $testimonial->saveFile(true);

                //Price Plan
                $testimonial = new JsonDataModifier('', 'price-plan');
                $testimonial->replaceColumnLanguage(["title"], $request->lang, $request->slug);
                $testimonial->replaceColumnLanguage(["features"], $request->lang, $request->slug);
                $testimonial->replaceColumnLanguage(["not_available_features"], $request->lang, $request->slug);
                $testimonial->saveFile(true);

                //widget
                $widget = new JsonDataModifier('', 'widgets');
                $widget->replaceColumnLanguage_for_widget(["widget_content"], $request->slug, $request->lang);
                $widget->saveFile(true);

                //donation home page header
                $donation_home_header = new JsonDataModifier('home-pages', 'donation-layout');
                $donation_home_header->replaceColumnLanguage_for_page_builder(["addon_settings"], $request->slug, $request->lang);
                $donation_home_header->saveFile(true);
            }
        }

        file_put_contents($only_path, json_encode($all_data_decoded));
        return redirect()->back()->with(FlashMsg::item_update());
    }
}
