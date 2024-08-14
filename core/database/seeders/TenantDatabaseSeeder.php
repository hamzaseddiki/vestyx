<?php

namespace Database\Seeders;

use Database\Seeders\Tenant\AdminSeed;
use Database\Seeders\Tenant\AllPages\AllAddons;
use Database\Seeders\Tenant\AllPages\DefaultPages;
use Database\Seeders\Tenant\GeneralData;
use Database\Seeders\Tenant\MediaSeed;
use Database\Seeders\Tenant\ModuleData\Appointment\AppointmentDataSeed;
use Database\Seeders\Tenant\ModuleData\Blog\AdvertisementSeed;
use Database\Seeders\Tenant\ModuleData\Blog\BlogCategorySeed;
use Database\Seeders\Tenant\ModuleData\Blog\BlogSeed;
use Database\Seeders\Tenant\ModuleData\CommonDescriptionSeed;
use Database\Seeders\Tenant\ModuleData\Donation\DonationActivityCategorySeed;
use Database\Seeders\Tenant\ModuleData\Donation\DonationActivitySeed;
use Database\Seeders\Tenant\ModuleData\Donation\DonationCategorySeed;
use Database\Seeders\Tenant\ModuleData\Donation\DonationSeed;
use Database\Seeders\Tenant\ModuleData\eCommerce\eCommerceDataSeed;
use Database\Seeders\Tenant\ModuleData\Event\EventCategorySeed;
use Database\Seeders\Tenant\ModuleData\Event\EventSeed;
use Database\Seeders\Tenant\ModuleData\FormBuilderSeed;
use Database\Seeders\Tenant\ModuleData\HotelBooking\CurrentHomeSeed;
use Database\Seeders\Tenant\ModuleData\HotelBooking\footerWidgetSeed;
use Database\Seeders\Tenant\ModuleData\HotelBooking\hotelBookingLayoutSeed;
use Database\Seeders\Tenant\ModuleData\Job\JobCategorySeed;
use Database\Seeders\Tenant\ModuleData\Job\JobSeed;
use Database\Seeders\Tenant\ModuleData\Knowledgebase\KnowledgebaseCategorySeed;
use Database\Seeders\Tenant\ModuleData\Knowledgebase\KnowledgebaseSeed;
use Database\Seeders\Tenant\ModuleData\Others\BrandSeed;
use Database\Seeders\Tenant\ModuleData\Others\FaqCategorySeed;
use Database\Seeders\Tenant\ModuleData\Others\FaqSeed;
use Database\Seeders\Tenant\ModuleData\Others\NewsletterSeed;
use Database\Seeders\Tenant\ModuleData\Others\TestimonialSeed;
use Database\Seeders\Tenant\ModuleData\Portfolio\PortfolioCategorySeed;
use Database\Seeders\Tenant\ModuleData\Portfolio\PortfolioSeed;
use Database\Seeders\Tenant\ModuleData\Service\ServiceCategorySeed;
use Database\Seeders\Tenant\ModuleData\Service\ServiceSeed;
use Database\Seeders\Tenant\ModuleData\WidgetSeed;
use Database\Seeders\Tenant\PaymentGatewayFieldsSeed;
use Database\Seeders\Tenant\RolePermissionSeed;
use Database\Seeders\Tenant\ModuleData\LanguageSeed;
use Database\Seeders\Tenant\ModuleData\MenuSeed;

use Database\Seeders\Tenant\PaymentLogs\DonationPaymentSeed;
use Database\Seeders\Tenant\PaymentLogs\EventPaymentSeed;
use Database\Seeders\Tenant\PaymentLogs\JobPaymentSeed;

use Database\Seeders\Tenant\Comments\DonationCommentSeed;
use Database\Seeders\Tenant\Comments\EventCommentSeed;

use Database\Seeders\Tenant\TenantDemoDataSeed;
use Database\Seeders\Tenant\WeddingPricePlanSeed;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;

use Database\Seeders\Tenant\ModuleData\Gallery\GalleryCategorySeed;
use Database\Seeders\Tenant\ModuleData\Gallery\GallerySeed;
use Database\Seeders\Tenant\ModuleData\Others\SupportTicketCategorySeed;

class TenantDatabaseSeeder extends Seeder
{
    public function run()
    {
        $package = tenant()->payment_log()->first()?->package()->first() ?? [];
        $all_features = $package->plan_features ?? [];

        $payment_log = tenant()->payment_log()?->first() ?? [];

        if(empty($all_features) && $payment_log->status != 'trial'){
            return;
        }

        $check_feature_name = $all_features->pluck('feature_name')->toArray();

        RolePermissionSeed::process_seeding();
        AdminSeed::run();
        LanguageSeed::run();
        CurrentHomeSeed::run();
        hotelBookingLayoutSeed::run();
        footerWidgetSeed::run();
        GeneralData::excute();
        DefaultPages::execute(); //Dynamic pages seed with home page layout
        MediaSeed::run();
        NewsletterSeed::execute();

        if (in_array('blog',$check_feature_name)) {
            BlogCategorySeed::run();
            BlogSeed::execute();
        }

        if (in_array('advertisement',$check_feature_name)) {
            AdvertisementSeed::execute();
        }

        if (in_array('donation',$check_feature_name)) {
            DonationCategorySeed::execute();
            DonationSeed::execute();
            DonationActivityCategorySeed::execute();
            DonationActivitySeed::execute();
        }

        if (in_array('faq',$check_feature_name)) {
            FaqCategorySeed::execute();
            FaqSeed::execute();
        }

        // The permission checked of this items in inside
            EventCategorySeed::execute();
            EventSeed::execute();
            JobCategorySeed::execute();
            JobSeed::execute();
            KnowledgebaseCategorySeed::execute();
            KnowledgebaseSeed::execute();
            PortfolioCategorySeed::execute();
            PortfolioSeed::execute();
        // The permission checked of this items in inside

         if (in_array('wedding_price_plan',$check_feature_name)) {
            WeddingPricePlanSeed::excute();
         }

        if (in_array('brand',$check_feature_name)) {
            BrandSeed::execute();
        }

        if (in_array('service',$check_feature_name)) {
            ServiceCategorySeed::execute();
            ServiceSeed::execute();
        }

        if (in_array('testimonial',$check_feature_name)) {
            TestimonialSeed::execute();
        }

        if (in_array('eCommerce',$check_feature_name)) {
            eCommerceDataSeed::execute();
        }

        if (in_array('appointment',$check_feature_name)) {
            AppointmentDataSeed::execute();
        }

        // The permission checked of this items in inside
           PaymentGatewayFieldsSeed::execute();
        // The permission checked of this items in inside

        // tenantDemoDataSeed
           TenantDemoDataSeed::execute();
        // end

        AllAddons::execute(); //Other pages seed except home page data
        WidgetSeed::execute();
        FormBuilderSeed::execute();

        //Payment log tables only checking table created or not
        DonationPaymentSeed::execute();
        EventPaymentSeed::execute();
        JobPaymentSeed::execute();

        //Comments
        if (in_array('donation',$check_feature_name)) {
            DonationCommentSeed::execute();
        }
        if (in_array('event',$check_feature_name)) {
            EventCommentSeed::execute();
        }

        //Others
        if (in_array('gallery',$check_feature_name)) {
            GalleryCategorySeed::execute();
            GallerySeed::execute();
        }
        SupportTicketCategorySeed::execute();


        //Directory check or create
        $css_path = 'assets/tenant/frontend/themes/css/dynamic-styles/';
        $js_path = 'assets/tenant/frontend/themes/js/dynamic-scripts/';
        if(!\File::isDirectory($css_path) && !\File::isDirectory($js_path)){
            \File::makeDirectory($css_path, 0777, true, true);
            \File::makeDirectory($js_path, 0777, true, true);
        }

        //Dynamic assets set
        $dynamic_css_path = 'assets/tenant/frontend/themes/css/dynamic-styles/'.tenant()->id.'-style.css';
        $dynamic_js_path = 'assets/tenant/frontend/themes/js/dynamic-scripts/'.tenant()->id.'-script.js';
        $css_comment_string = '/*Write Css*/';
        $js_comment_string = '//Write js';

        file_put_contents($dynamic_css_path,$css_comment_string);
        file_put_contents($dynamic_js_path,$js_comment_string);

        //default theme setting
        $session_trial_theme_or_default = session()->get('theme') ?? get_static_option_central('landlord_default_theme_set'); //for trial theme
        $theme = optional(tenant()->payment_log)->theme ? optional(tenant()->payment_log)->theme : $session_trial_theme_or_default;
        update_static_option('tenant_default_theme',$theme);

        //Some switcher data
        update_static_option('landlord_frontend_contact_info_show_hide','on');
        update_static_option('landlord_frontend_social_info_show_hide','on');

        //page setup according theme
        update_static_option('donation_page',12);
        update_static_option('job_page',14);
        update_static_option('event_page',16);
        update_static_option('knowledgebase_page',17);
        update_static_option('terms_condition_page',22);
        update_static_option('privacy_policy_page',24);
        update_static_option('shop_page',21);

    }


}
