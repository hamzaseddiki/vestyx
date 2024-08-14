<?php


namespace Plugins\PageBuilder;

use App\Helpers\ModuleMetaData;
use App\Models\PageBuilder;
use Plugins\PageBuilder\Addons\Landlord\About\AboutUs;
use Plugins\PageBuilder\Addons\Landlord\About\CountArea;
use Plugins\PageBuilder\Addons\Landlord\Common\Brand;
use Plugins\PageBuilder\Addons\Landlord\Common\ContactArea;
use Plugins\PageBuilder\Addons\Landlord\Common\ContactCards;
use Plugins\PageBuilder\Addons\Landlord\Common\FaqOne;
use Plugins\PageBuilder\Addons\Landlord\Common\LandlordBlogArea;
use Plugins\PageBuilder\Addons\Landlord\Common\Newsletter;
use Plugins\PageBuilder\Addons\Landlord\Common\OnlyImage;
use Plugins\PageBuilder\Addons\Landlord\Common\PricePlan;
use Plugins\PageBuilder\Addons\Landlord\Common\RawHtml;
use Plugins\PageBuilder\Addons\Landlord\Common\TeamMemberOne;
use Plugins\PageBuilder\Addons\Landlord\Common\TemplateDesign;
use Plugins\PageBuilder\Addons\Landlord\Common\TestimonialOne;
use Plugins\PageBuilder\Addons\Landlord\Common\TextEditor;
use Plugins\PageBuilder\Addons\Landlord\Common\VideoArea;
use Plugins\PageBuilder\Addons\Landlord\Common\WhyChooseUs;
use Plugins\PageBuilder\Addons\Landlord\Home\HeaderStyleOne;
use Plugins\PageBuilder\Addons\Tenants\Agency\AboutArea;
use Plugins\PageBuilder\Addons\Tenants\Agency\BlogAreaAgency;
use Plugins\PageBuilder\Addons\Tenants\Agency\Counterup;
use Plugins\PageBuilder\Addons\Tenants\Agency\HeaderAgency;
use Plugins\PageBuilder\Addons\Tenants\Agency\ServiceArea;
use Plugins\PageBuilder\Addons\Tenants\Agency\TestimonialAgency;
use Plugins\PageBuilder\Addons\Tenants\Agency\WorkArea;
use Plugins\PageBuilder\Addons\Tenants\BarberShop\AppointmentArea;
use Plugins\PageBuilder\Addons\Tenants\BarberShop\ShopArea;
use Plugins\PageBuilder\Addons\Tenants\Common\about\AboutUsOne;
use Plugins\PageBuilder\Addons\Tenants\Common\about\MissionAreaOne;
use Plugins\PageBuilder\Addons\Tenants\Common\Contact\ContactAreaOne;
use Plugins\PageBuilder\Addons\Tenants\Common\Contact\ContactFormWithoutMap;
use Plugins\PageBuilder\Addons\Tenants\Common\misc\BlogOne;
use Plugins\PageBuilder\Addons\Tenants\Common\misc\BrandOne;
use Plugins\PageBuilder\Addons\Tenants\Common\misc\BrandTwo;
use Plugins\PageBuilder\Addons\Tenants\Common\misc\FaqThree;
use Plugins\PageBuilder\Addons\Tenants\Common\misc\FaqTwo;
use Plugins\PageBuilder\Addons\Tenants\Common\misc\ImageGalleryOne;
use Plugins\PageBuilder\Addons\Tenants\Common\misc\PortfolioOne;
use Plugins\PageBuilder\Addons\Tenants\Common\misc\ServiceOne;
use Plugins\PageBuilder\Addons\Tenants\Common\misc\TeamMemberTwo;
use Plugins\PageBuilder\Addons\Tenants\Common\misc\TestimonialFive;
use Plugins\PageBuilder\Addons\Tenants\Common\misc\TestimonialFour;
use Plugins\PageBuilder\Addons\Tenants\Common\misc\TestimonialThree;
use Plugins\PageBuilder\Addons\Tenants\Common\misc\TestimonialTwo;
use Plugins\PageBuilder\Addons\Tenants\Common\misc\VideoGalleryOne;
use Plugins\PageBuilder\Addons\Tenants\Construction\BlogArea;
use Plugins\PageBuilder\Addons\Tenants\Construction\CounterupArea;
use Plugins\PageBuilder\Addons\Tenants\Construction\HeaderArea;
use Plugins\PageBuilder\Addons\Tenants\Construction\ProjectArea;
use Plugins\PageBuilder\Addons\Tenants\Construction\TestimonialArea;
use Plugins\PageBuilder\Addons\Tenants\Consultancy\FaqArea;
use Plugins\PageBuilder\Addons\Tenants\Consultancy\ServiceAreaTwo;
use Plugins\PageBuilder\Addons\Tenants\Consultancy\TeamMemberArea;
use Plugins\PageBuilder\Addons\Tenants\Donation\AboutDonation;
use Plugins\PageBuilder\Addons\Tenants\Donation\BlogSliderOne;
use Plugins\PageBuilder\Addons\Tenants\Donation\DonationActivities;
use Plugins\PageBuilder\Addons\Tenants\Donation\DonationContactArea;
use Plugins\PageBuilder\Addons\Tenants\Donation\DonationTestimonial;
use Plugins\PageBuilder\Addons\Tenants\Donation\DonationWithFilter;
use Plugins\PageBuilder\Addons\Tenants\Donation\HeaderOne;
use Plugins\PageBuilder\Addons\Tenants\Donation\RecentCampaign;
use Plugins\PageBuilder\Addons\Tenants\Donation\ServiceDonation;
use Plugins\PageBuilder\Addons\Tenants\eCommerce\AllCampaigns;
use Plugins\PageBuilder\Addons\Tenants\eCommerce\AllProduct;
use Plugins\PageBuilder\Addons\Tenants\eCommerce\BestDeal;
use Plugins\PageBuilder\Addons\Tenants\eCommerce\EcommerceHeader;
use Plugins\PageBuilder\Addons\Tenants\eCommerce\eCommerceServices;
use Plugins\PageBuilder\Addons\Tenants\eCommerce\eCommerceSubscribeNewsletter;
use Plugins\PageBuilder\Addons\Tenants\eCommerce\HotDeal;
use Plugins\PageBuilder\Addons\Tenants\eCommerce\OfferArea;
use Plugins\PageBuilder\Addons\Tenants\eCommerce\ProductCampaigns;
use Plugins\PageBuilder\Addons\Tenants\eCommerce\ProductSlider;
use Plugins\PageBuilder\Addons\Tenants\Event\AboutEventOne;
use Plugins\PageBuilder\Addons\Tenants\Event\EventCounterupOne;
use Plugins\PageBuilder\Addons\Tenants\Event\EventSliderOne;
use Plugins\PageBuilder\Addons\Tenants\Event\EventSpeakers;
use Plugins\PageBuilder\Addons\Tenants\Event\EventSubscribeNewsletter;
use Plugins\PageBuilder\Addons\Tenants\Event\EventTestimonial;
use Plugins\PageBuilder\Addons\Tenants\Event\EventWithFilter;
use Plugins\PageBuilder\Addons\Tenants\Event\HeaderSlider;
use Plugins\PageBuilder\Addons\Tenants\Event\HeaderTwo;
use Plugins\PageBuilder\Addons\Tenants\Job\AboutJobOne;
use Plugins\PageBuilder\Addons\Tenants\Job\BlogSliderTwo;
use Plugins\PageBuilder\Addons\Tenants\Job\HeaderThree;
use Plugins\PageBuilder\Addons\Tenants\Job\JobCategory;
use Plugins\PageBuilder\Addons\Tenants\Job\JobCircular;
use Plugins\PageBuilder\Addons\Tenants\Job\JobSubscribeNewsletter;
use Plugins\PageBuilder\Addons\Tenants\Job\JobWithFilter;
use Plugins\PageBuilder\Addons\Tenants\Knowledgebase\AboutKnowledgebaseOne;
use Plugins\PageBuilder\Addons\Tenants\Knowledgebase\HeaderFour;
use Plugins\PageBuilder\Addons\Tenants\Knowledgebase\Knowledgebase;
use Plugins\PageBuilder\Addons\Tenants\Newspaper\Advertisement;
use Plugins\PageBuilder\Addons\Tenants\Newspaper\EntertainmentArea;
use Plugins\PageBuilder\Addons\Tenants\Newspaper\HeaderNewspaper;
use Plugins\PageBuilder\Addons\Tenants\Newspaper\HighlightAreaNewspaper;
use Plugins\PageBuilder\Addons\Tenants\Newspaper\NewspaperSlider;
use Plugins\PageBuilder\Addons\Tenants\Newspaper\PopularAreaNews;
use Plugins\PageBuilder\Addons\Tenants\Newspaper\SportsAreaNewspaper;
use Plugins\PageBuilder\Addons\Tenants\Photography\StoryArea;
use Plugins\PageBuilder\Addons\Tenants\Photography\TestimonialPhotography;
use Plugins\PageBuilder\Addons\Tenants\Portfolio\SpecialityArea;
use Plugins\PageBuilder\Addons\Tenants\Portfolio\TestimonialPortfolio;
use Plugins\PageBuilder\Addons\Tenants\SoftwareBusiness\BusinessArea;
use Plugins\PageBuilder\Addons\Tenants\Ticket\AboutTicket;
use Plugins\PageBuilder\Addons\Tenants\Ticket\HeaderFive;
use Plugins\PageBuilder\Addons\Tenants\Ticket\TicketContactArea;
use Plugins\PageBuilder\Addons\Tenants\Ticket\WhyChooseArea;
use Plugins\PageBuilder\Addons\Tenants\Wedding\ActivitiesArea;
use Plugins\PageBuilder\Addons\Tenants\Wedding\HeaderBottomArea;
use Plugins\PageBuilder\Addons\Tenants\Wedding\MakeArea;
use Plugins\PageBuilder\Addons\Tenants\Wedding\NeedArea;
use Plugins\PageBuilder\Addons\Tenants\Wedding\PricePlanArea;


class PageBuilderSetup
{

    public static function ecommerceAddons(){
        return [
            EcommerceHeader::class,
            OfferArea::class,
            ProductSlider::class,
            AllProduct::class,
            BestDeal::class,
            ProductCampaigns::class,
            HotDeal::class,
            eCommerceSubscribeNewsletter::class,
            eCommerceServices::class,
            AllCampaigns::class,
        ];
    }
    private static function registerd_widgets(): array
    {
        //check module wise widget by set condition
        $addonLists = [
            //Admin Register
            HeaderStyleOne::class,
            Brand::class,
            WhyChooseUs::class,
            TemplateDesign::class,
            PricePlan::class,
            TestimonialOne::class,
            FaqOne::class,
            Newsletter::class,
            AboutUs::class,
            CountArea::class,
            TeamMemberOne::class,
            ContactCards::class,
            ContactArea::class,
            OnlyImage::class,
            TextEditor::class,
            RawHtml::class,
            LandlordBlogArea::class,
            \Plugins\PageBuilder\Addons\Landlord\Common\HeaderSlider::class,
            VideoArea::class,


            //Tenant
            ContactAreaOne::class,
            AboutUsOne::class,
            \Plugins\PageBuilder\Addons\Tenants\Common\misc\TeamMemberOne::class,
            MissionAreaOne::class,
            \Plugins\PageBuilder\Addons\Tenants\Common\misc\TestimonialOne::class,
            BrandOne::class,
            \Plugins\PageBuilder\Addons\Tenants\Common\misc\FaqOne::class,
            ImageGalleryOne::class,
            VideoGalleryOne::class,
            TeamMemberTwo::class,
            TestimonialTwo::class,
            PortfolioOne::class,
            BlogOne::class,
            ServiceOne::class,

            HeaderOne::class,
            \Plugins\PageBuilder\Addons\Tenants\Donation\HeaderSlider::class,
            BrandTwo::class,
            RecentCampaign::class,
            AboutDonation::class,
            ServiceDonation::class,
            DonationActivities::class,
            DonationTestimonial::class,
            BlogSliderOne::class,
            DonationWithFilter::class,
            HeaderTwo::class,
            DonationContactArea::class,
            EventSliderOne::class,
            AboutEventOne::class,
            EventCounterupOne::class,
            EventSpeakers::class,
            HeaderSlider::class,
            EventTestimonial::class,
            EventSubscribeNewsletter::class,
            EventWithFilter::class,
            HeaderThree::class,
            JobCategory::class,
            AboutJobOne::class,
            JobCircular::class,
            TestimonialThree::class,
            BlogSliderTwo::class,
            JobSubscribeNewsletter::class,
            JobWithFilter::class,
            HeaderFour::class,
            Knowledgebase::class,
            AboutKnowledgebaseOne::class,
            TestimonialFour::class,
            FaqTwo::class,
            HeaderFive::class,
            WhyChooseArea::class,
            AboutTicket::class,
            TestimonialFive::class,
            FaqThree::class,
            TicketContactArea::class,



            HeaderAgency::class,
            \Plugins\PageBuilder\Addons\Tenants\Agency\HeaderSlider::class,
            Counterup::class,
            AboutArea::class,
            ServiceArea::class,
            WorkArea::class,
            TestimonialAgency::class,
            BlogAreaAgency::class,
            \Plugins\PageBuilder\Addons\Tenants\Agency\ContactArea::class,

            HeaderNewspaper::class,
            Advertisement::class,
            NewspaperSlider::class,
            PopularAreaNews::class,
            EntertainmentArea::class,
            SportsAreaNewspaper::class,
            HighlightAreaNewspaper::class,
            HeaderArea::class,
            \Plugins\PageBuilder\Addons\Tenants\Construction\ServiceArea::class,
            \Plugins\PageBuilder\Addons\Tenants\Construction\OfferArea::class,
            CounterupArea::class,
            ProjectArea::class,
            TestimonialArea::class,
            BlogArea::class,
            \Plugins\PageBuilder\Addons\Tenants\Construction\ContactArea::class,
            \Plugins\PageBuilder\Addons\Tenants\Consultancy\HeaderArea::class,
            \Plugins\PageBuilder\Addons\Tenants\Consultancy\ServiceArea::class,
            \Plugins\PageBuilder\Addons\Tenants\Consultancy\AboutArea::class,
            TeamMemberArea::class,
            FaqArea::class,
            \Plugins\PageBuilder\Addons\Tenants\Consultancy\BlogArea::class,
            \Plugins\PageBuilder\Addons\Tenants\Consultancy\ContactArea::class,
            ServiceAreaTwo::class,

            \Plugins\PageBuilder\Addons\Tenants\Wedding\HeaderArea::class,
            HeaderBottomArea::class,
            MakeArea::class,
            NeedArea::class,
            ActivitiesArea::class,
            PricePlanArea::class,
            \Plugins\PageBuilder\Addons\Tenants\Wedding\TestimonialArea::class,
            \Plugins\PageBuilder\Addons\Tenants\Wedding\BlogArea::class,
            \Plugins\PageBuilder\Addons\Tenants\Wedding\ContactArea::class,
            \Plugins\PageBuilder\Addons\Tenants\Photography\HeaderArea::class,
            \Plugins\PageBuilder\Addons\Tenants\Photography\ServiceArea::class,
            StoryArea::class,
            \Plugins\PageBuilder\Addons\Tenants\Photography\WorkArea::class,
            TestimonialPhotography::class,
            \Plugins\PageBuilder\Addons\Tenants\Photography\BlogArea::class,
            \Plugins\PageBuilder\Addons\Tenants\Photography\ContactArea::class,
            \Plugins\PageBuilder\Addons\Tenants\Portfolio\HeaderArea::class,
            SpecialityArea::class,
            \Plugins\PageBuilder\Addons\Tenants\Portfolio\AboutArea::class,
            \Plugins\PageBuilder\Addons\Tenants\Portfolio\WorkArea::class,
            TestimonialPortfolio::class,
            \Plugins\PageBuilder\Addons\Tenants\Portfolio\ContactArea::class,
            \Plugins\PageBuilder\Addons\Tenants\SoftwareBusiness\HeaderArea::class,
            \Plugins\PageBuilder\Addons\Tenants\SoftwareBusiness\HeaderBottomArea::class,
            BusinessArea::class,
            \Plugins\PageBuilder\Addons\Tenants\SoftwareBusiness\ServiceArea::class,
            \Plugins\PageBuilder\Addons\Tenants\SoftwareBusiness\WorkArea::class,
            \Plugins\PageBuilder\Addons\Tenants\SoftwareBusiness\BlogArea::class,
            \Plugins\PageBuilder\Addons\Tenants\SoftwareBusiness\TestimonialArea::class,
            \Plugins\PageBuilder\Addons\Tenants\SoftwareBusiness\ContactArea::class,

            //Barber shop
            \Plugins\PageBuilder\Addons\Tenants\BarberShop\HeaderArea::class,
            AppointmentArea::class,
            \Plugins\PageBuilder\Addons\Tenants\BarberShop\AboutArea::class,
            ShopArea::class,
            \Plugins\PageBuilder\Addons\Tenants\BarberShop\WorkArea::class,
            \Plugins\PageBuilder\Addons\Tenants\BarberShop\TestimonialArea::class,
            \Plugins\PageBuilder\Addons\Tenants\BarberShop\ContactArea::class,
            \Plugins\PageBuilder\Addons\Tenants\BarberShop\BlogArea::class,
            ContactFormWithoutMap::class

        ];

        //Check feature permission
        if(tenant()){
            $package = tenant()->user()->first()?->payment_log()->first()?->package()->first() ?? [];
            $all_features = $package->plan_features ?? [];
            $check_feature_name = $all_features->pluck('feature_name')->toArray();

            if(in_array('eCommerce',$check_feature_name)){
                foreach(self::ecommerceAddons() as $item){
                    $addonLists[] = $item;
                }
            }

            // Third party custom addons
            $customAddons = (new ModuleMetaData())->getTenantPageBuilderAddonList();

            return array_merge($addonLists, $customAddons);
        }

        return $addonLists;
    }

    public static function get_tenant_admin_panel_widgets(): string
    {
        $widgets_markup = '';
        $widget_list = self::tenant_registerd_widgets();

        foreach ($widget_list as $widget){

            try {
                $widget_instance = new  $widget();
            }catch (\Exception $e){
                $msg = $e->getMessage();
                throw new \ErrorException($msg);
            }
            if ($widget_instance->enable()){
                $widgets_markup .= self::render_admin_addon_item([
                    'addon_name' => $widget_instance->addon_name(),
                    'addon_namespace' => $widget_instance->addon_namespace(), // new added
                    'addon_title' => $widget_instance->addon_title(),
                    'preview_image' => $widget_instance->get_preview_image($widget_instance->preview_image())
                ]);
            }

        }
        return $widgets_markup;
    }

    public static function get_admin_panel_widgets(): string
    {
        $widgets_markup = '';
        $widget_list = self::registerd_widgets();
        foreach ($widget_list as $widget){
            try {
                $widget_instance = new $widget();
            }catch (\Exception $e){
                $msg = $e->getMessage();
                throw new \ErrorException($msg);
            }
            if ($widget_instance->enable()){
                $widgets_markup .= self::render_admin_addon_item([
                    'addon_name' => $widget_instance->addon_name(),
                    'addon_namespace' => $widget_instance->addon_namespace(), // new added
                    'addon_title' => $widget_instance->addon_title(),
                    'preview_image' => $widget_instance->get_preview_image($widget_instance->preview_image())
                ]);
            }

        }
        return $widgets_markup;
    }

    private static function render_admin_addon_item($args): string
    {
        return '<li class="ui-state-default widget-handler" data-name="'.$args['addon_name'].'" data-namespace="'.base64_encode($args['addon_namespace']).'" >
                    <h4 class="top-part"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>'.$args['addon_title'].$args['preview_image'].'</h4>
                </li>';
    }
    public static function render_widgets_by_name_for_admin($args){
        $widget_class = $args['namespace'];

        $instance = new $widget_class($args);
        if ($instance->enable()){
            return $instance->admin_render();
        }
    }

    public static function render_widgets_by_name_for_frontend($args){
        $widget_class = $args['namespace'];
        $instance = new $widget_class($args);

        if ($instance->enable()){
            return $instance->frontend_render();
        }
    }

    public static function render_frontend_pagebuilder_content_by_location($location): string
    {
        $output = '';
        $all_widgets = PageBuilder::where(['addon_location' => $location])->orderBy('addon_order', 'ASC')->get();
        foreach ($all_widgets as $widget) {
            $output .= self::render_widgets_by_name_for_frontend([
                'name' => $widget->addon_name,
                'namespace' => $widget->addon_namespace,
                'location' => $location,
                'id' => $widget->id,
                'column' => $args['column'] ?? false
            ]);
        }
        return $output;
    }

    public static function get_saved_addons_by_location($location): string
    {
        $output = '';
        $all_widgets = PageBuilder::where(['addon_location' => $location])->orderBy('addon_order','asc')->get();
        foreach ($all_widgets as $widget) {
            $output .= self::render_widgets_by_name_for_admin([
                'name' => $widget->addon_name,
                'namespace' => $widget->addon_namespace,
                'id' => $widget->id,
                'type' => 'update',
                'order' => $widget->addon_order,
                'page_type' => $widget->addon_page_type,
                'page_id' => $widget->addon_page_id,
                'location' => $widget->addon_location
            ]);
        }

        return $output;
    }
    public static function get_saved_addons_for_dynamic_page($page_type,$page_id): string
    {
        $output = '';
        $all_widgets = PageBuilder::where(['addon_page_type' => $page_type,'addon_page_id' => $page_id])->orderBy('addon_order','asc')->get();
        foreach ($all_widgets as $widget) {
            $output .= self::render_widgets_by_name_for_admin([
                'name' => $widget->addon_name,
                'namespace' => $widget->addon_namespace,
                'id' => $widget->id,
                'type' => 'update',
                'order' => $widget->addon_order,
                'page_type' => $widget->addon_page_type,
                'page_id' => $widget->addon_page_id,
                'location' => $widget->addon_location
            ]);
        }

        return $output;
    }
    public static function render_frontend_pagebuilder_content_for_dynamic_page($page_type,$page_id): string
    {
        $output = '';
        $all_widgets = PageBuilder::where(['addon_page_type' => $page_type,'addon_page_id' => $page_id])->orderBy('addon_order','asc')->get();
        foreach ($all_widgets as $widget) {
            $output .= self::render_widgets_by_name_for_frontend([
                'name' => $widget->addon_name,
                'namespace' => $widget->addon_namespace,
                'id' => $widget->id,
                'column' => $args['column'] ?? false
            ]);
        }
        return $output;
    }
}
