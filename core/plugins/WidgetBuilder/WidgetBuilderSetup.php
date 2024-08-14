<?php


namespace Plugins\WidgetBuilder;

use App\Helpers\ModuleMetaData;

class WidgetBuilderSetup
{
    private static function registerd_widgets(){
        $widget = [
            'RawHTMLWidget',
            'TextEditorWidget',
            'AboutUsWidget',
            'BlogSearchWidget',
            'RecentBlogPostWidget',
            'BlogCategoryWidget',
            'TenantNavigationMenuWidget',
            'TenantContactInfoWidget',
            'TenantBrandOne',
            'TenantNewsletterOne',
            'TenantServiceCategoryWidget',
            'TenantCustomFormWidget',
            'CustomPageWithLinkWidget',
            'ContactInfoWidget',
            'TenantRecentBlogPostWidget',
            'BlogCategoryWithSearchWidget',
            'TenantServiceCategoryWithSearchWidget',
            'TenantRecentServiceWidget',
            'TenantQuerySubmitWidget',
            'TenantRecentDonationWidget',
            'TenantDonationCategoryWidget',
            'TenantRecentDonationActivitiesWidget',
            'TenantDonationActivityCategoryWidget',
            'TenantRecentEventsWidget',
            'TenantEventCategoryWithSearchWidget',
            'TenantJobCategoryWithSearchWidget',
            'TenantRecentJobsWidget',
            'TenantRecentKnowledgebaseWidget',
            'TenantKnowledgebaseCategoryWithSearchWidget',
            'TenantPopularAppointmentWidget',
            'TenantAppointmentCategoryWithSearchWidget',
            'TenantPopularSubAppointmentWidget',

            //Tenant Footer
            'TenantFooterAboutUsWidget',
            'TenantFooterCustomLink',
            'TenantFooterRecentEventsWidget',
            'TenantNewsletterSubmitWidget',
            'TenantSocialMediaOne',
            'Advertise',
            'TenantNewsCategoryWidget',
            'TenantNewsNewsletterWidget',
            'TenantPopularNewsWidget',
            'TenantMapWidget',
            'TenantHotelBookingAboutUsWidget',
            'TenantHotelBookingSocialWidget',
            'TenantHotelBookingLinkWidget',
            'TenantHotelBookingContactWidget',
        ];

        $customAddons = (new ModuleMetaData())->getWidgetBuilderAddonList();

        return array_merge($widget, $customAddons);
    }
    private static function registerd_sidebars(){

        $register_widgets_for_admin = ['footer','blog_sidebar'];
        $condition = tenant() ? self::get_widget_by_theme() : $register_widgets_for_admin;
        return $condition;
    }

    private static function get_widget_by_theme(){

        $common = ['blog_sidebar','service_sidebar'];
        $tenant_widgets = [];

        switch (get_static_option('tenant_default_theme')){
            case 'donation':
                $tenant_widgets = ['footer_donation','donation_sidebar','donation_acitvities_sidebar'];
                break;

            case 'event':
                $tenant_widgets = ['footer_event','event_sidebar'];
                break;

            case 'job-find':
                $tenant_widgets = ['footer_job','job_sidebar'];
                break;

            case 'article-listing':
                $tenant_widgets = ['footer_knowledgebase','knowledgebase_sidebar'];
                break;

            case 'support-ticketing':
                $tenant_widgets = ['footer_ticket'];
                break;

            case 'eCommerce':
                $tenant_widgets = ['footer_eCommerce'];
                break;

            case 'agency':
                $tenant_widgets = ['footer_agency'];
                break;

            case 'newspaper':
                $tenant_widgets = ['newspaper_footer','newspaper_sidebar','newspaper_sidebar_two'];
                break;

            case 'construction':
                $tenant_widgets = ['construction_footer'];
                break;

            case 'consultancy':
                $tenant_widgets = ['consultancy_footer'];
                break;

            case 'wedding':
                $tenant_widgets = ['wedding_footer'];
                break;

            case 'photography':
                $tenant_widgets = ['photography_footer'];
                break;

            case 'portfolio':
                $tenant_widgets = ['portfolio_footer'];
                break;

            case 'software-business':
                $tenant_widgets = ['software_business_footer'];
                break;

            case 'barber-shop':
                $tenant_widgets = ['barber_shop_footer','appointment_sidebar','sub_appointment_sidebar'];
                break;

            case 'hotel-booking':
                $tenant_widgets = ['hotel_booking_footer','hotel_booking_bottom_footer'];
                break;
        }

        return array_merge($common,$tenant_widgets);
    }


    public static function get_admin_widget_sidebar_list(){
        $all_sidebar = self::registerd_sidebars();
        $output = '';
        foreach ($all_sidebar as $sidebar){
            $output .= self::render_admin_sidebar_item($sidebar);
        }
        return $output;
    }

    public static function get_admin_panel_widgets(){
        $widgets_markup = '';
        $widget_list = self::registerd_widgets();
        foreach ($widget_list as $widget){
            $namespace = __NAMESPACE__."\Widgets\\".$widget;
            $widget_instance = new  $namespace();
            if ($widget_instance->enable()){
                $widgets_markup .= self::render_admin_widget_item([
                    'widget_name' => $widget_instance->widget_name(),
                    'widget_title' => $widget_instance->widget_title()
                ]);
            }

        }
        return $widgets_markup;
    }

    private static function render_admin_widget_item($args){
        return '<li class="ui-state-default widget-handler" data-name="'.$args['widget_name'].'">
                    <h4 class="top-part"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span>'.$args['widget_title'].'</h4>
                </li>';
    }
    public static function render_admin_sidebar_item($sidebar){
        $markup = '<div class="card">
                    <div class="card-header widget-area-header">
                        <h4 class="header-title">'.ucfirst(str_replace('_',' ',$sidebar)).' '.__('Widgets Area').'</h4>
                        <span class="widget-area-expand"><i class="las la-angle-down"></i></span>
                    </div>
                    <div class="card-body widget-area-body hide">
                        <ul id="'.$sidebar.'" class="sortable available-form-field main-fields sortable_widget_location">
                            '.render_admin_saved_widgets($sidebar).'
                        </ul>
                    </div>
                </div>';
        return $markup;
    }
    public static function render_widgets_by_name_for_admin($args){

        //widget_name
        $widget_class = 'Plugins\WidgetBuilder\Widgets\\'.$args['name'];
        $instance = new $widget_class($args);
        $before = $args['before'] ?? true;
        $after = $args['after'] ?? true;
        if($instance->enable()){
            return $instance->admin_render(['before' => $before,'after' => $after]);
        }
    }

    public static function render_widgets_by_name_for_frontend($args){
        //widget_name
        $widget_class = 'Plugins\WidgetBuilder\Widgets\\'.$args['name'];
        $instance = new $widget_class($args);
        if($instance->enable()) {
            return $instance->frontend_render();
        }
    }
}
