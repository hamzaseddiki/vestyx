<?php


namespace App\Helpers;


use App\Models\PaymentLogs;
use App\Models\PricePlan;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use function __;

class SidebarMenuHelper
{

    public function render_sidebar_menus() : string
    {
        $menu_instance = new \App\Helpers\MenuWithPermission();

        $menu_instance->add_menu_item('dashboard-menu', [
            'route' => 'landlord.admin.home',
            'label' => __('Dashboard'),
            'parent' => null,
            'permissions' => ['dashboard'],
            'icon' => 'mdi mdi-view-dashboard',
        ]);

        $admin = \Auth::guard('admin')->user();

        if ($admin->hasRole('Super Admin')){
            $this->admin_manage_menus($menu_instance);
            $this->users_manage_menus($menu_instance);
        }

        $this->users_website_issues_manage_menus($menu_instance);

        $this->pages_settings_menus($menu_instance);
        $this->blog_settings_menus($menu_instance);
        $this->wallet_manage_settings_menus($menu_instance);
        $this->themes_settings_menus($menu_instance);
        $this->price_plan_settings_menus($menu_instance);
        $this->page_builder_seeder_settings_menus($menu_instance);
        $this->seeder_settings_menus($menu_instance);
        $this->coupon_settings_menus($menu_instance);
        $this->all_notification_settings_menus($menu_instance);
        $this->newsletter_settings_menus($menu_instance);
        $this->order_manage_settings_menus($menu_instance);
        $this->custom_domain_settings_menus($menu_instance);
        $this->support_ticket_settings_menus($menu_instance);
        $this->testimonial_settings_menus($menu_instance);
        $this->brands_settings_menus($menu_instance);
        $this->form_builder_settings_menus($menu_instance);
        $this->appearance_settings_menus($menu_instance);

        // External Menu Render
        foreach (getAllExternalMenu() as $externalMenu)
        {
            foreach ($externalMenu as $individual_menu_item){
                $convert_to_array = (array) $individual_menu_item;
                $routeName = $convert_to_array['route'];
                if (isset($routeName) && !empty($routeName) && Route::has($routeName)){
                    $menu_instance->add_menu_item($convert_to_array['id'], $convert_to_array);
                }
            }
        }

        $this->general_settings_menus($menu_instance);
        $this->payment_gateway_manage_menus($menu_instance);



        $menu_instance->add_menu_item('languages', [
            'route' => 'landlord.admin.languages',
            'label' => __('Languages'),
            'parent' => null,
            'permissions' => ['language-list','language-create','language-edit','language-delete'],
            'icon' => 'mdi mdi-language-css3',
        ]);
        return $menu_instance->render_menu_items();
    }

    private function pages_settings_menus(MenuWithPermission $menu_instance) : void
    {
        $menu_instance->add_menu_item('pages-settings-menu-items', [
            'route' => '#',
            'label' => __('Pages'),
            'parent' => null,
            'permissions' => ['page-list','page-create','page-edit','page-delete'],
            'icon' => 'mdi mdi-file',
        ]);
        $menu_instance->add_menu_item('pages-settings-all-page-settings', [
            'route' => 'landlord.admin.pages',
            'label' => __('All Pages'),
            'parent' => 'pages-settings-menu-items',
            'permissions' => ['page-list'],
        ]);
        $menu_instance->add_menu_item('pages-settings-new-page-settings', [
            'route' => 'landlord.admin.pages.create',
            'label' => __('New Pages'),
            'parent' => 'pages-settings-menu-items',
            'permissions' => ['page-create'],
        ]);
    }

    private function wallet_manage_settings_menus(MenuWithPermission $menu_instance): void
    {
        $menu_instance->add_menu_item('wallet-manage-settings-menu-items', [
            'route' => '#',
            'label' => __('Wallet Manage'),
            'parent' => null,
            'permissions' => ['wallet-list','wallet-history'],
            'icon' => 'mdi mdi-cash-register',
        ]);
        $menu_instance->add_menu_item('wallet-manage-settings-list-menu-items', [
            'route' => 'landlord.admin.wallet.lists',
            'label' => __('All Wallet'),
            'parent' => 'wallet-manage-settings-menu-items',
            'permissions' => ['wallet-list'],
        ]);
        $menu_instance->add_menu_item('wallet-manage-settings-history-menu-items', [
            'route' => 'landlord.admin.wallet.history',
            'label' => __('Wallet History'),
            'parent' => 'wallet-manage-settings-menu-items',
            'permissions' => ['wallet-history'],
        ]);
        $menu_instance->add_menu_item('wallet-manage-settings-admin-menu-items', [
            'route' => 'landlord.admin.wallet.settings',
            'label' => __('Wallet Settings'),
            'parent' => 'wallet-manage-settings-menu-items',
            'permissions' => ['wallet-history'],
        ]);
    }


    private function themes_settings_menus(MenuWithPermission $menu_instance): void
    {
        $menu_instance->add_menu_item('themes-settings-menu-items', [
            'route' => '#',
            'label' => __('Themes'),
            'parent' => null,
            'permissions' => ['theme-list', 'theme-edit'],
            'icon' => 'mdi mdi-shape-plus',
        ]);

        $menu_instance->add_menu_item('pages-settings-all-theme-settings', [
            'route' => 'landlord.admin.theme',
            'label' => __('All Themes'),
            'parent' => 'themes-settings-menu-items',
            'permissions' => ['theme-list'],
        ]);
        $menu_instance->add_menu_item('pages-settings-theme-settings', [
            'route' => 'landlord.admin.theme.settings',
            'label' => __('Themes Settings'),
            'parent' => 'themes-settings-menu-items',
            'permissions' => ['theme-settings'],
        ]);
        $menu_instance->add_menu_item('pages-settings-add-new-theme', [
            'route' => 'landlord.admin.add.theme',
            'label' => __('Add new Theme'),
            'parent' => 'themes-settings-menu-items',
            'permissions' => ['theme-settings'],
        ]);
    }

    private function price_plan_settings_menus(MenuWithPermission $menu_instance) : void
    {
        $menu_instance->add_menu_item('price-plan-settings-menu-items', [
            'route' => '#',
            'label' => __('Price Plan'),
            'parent' => null,
            'permissions' => ['price-plan-list','price-plan-create','price-plan-edit','price-plan-delete'],
            'icon' => 'mdi mdi-cash-multiple',
        ]);
        $menu_instance->add_menu_item('price-plan-settings-all-page-settings', [
            'route' => 'landlord.admin.price.plan',
            'label' => __('All Price Plan'),
            'parent' => 'price-plan-settings-menu-items',
            'permissions' => ['price-plan-list'],
        ]);
        $menu_instance->add_menu_item('price-plan-settings-new-page-settings', [
            'route' => 'landlord.admin.price.plan.create',
            'label' => __('New Price Plan'),
            'parent' => 'price-plan-settings-menu-items',
            'permissions' => ['price-plan-create'],
        ]);

        $menu_instance->add_menu_item('price-plan-settings-plan-settings', [
            'route' => 'landlord.admin.price.plan.settings',
            'label' => __('Settings'),
            'parent' => 'price-plan-settings-menu-items',
            'permissions' => [''],
        ]);
    }


    private function page_builder_seeder_settings_menus(MenuWithPermission $menu_instance) : void
    {
        $menu_instance->add_menu_item('home-pages-seeder-settings', [
            'route' => '#',
            'label' => __('Demo Page Builder Data '),
            'parent' => null,
            'permissions' => [],
            'icon' => 'mdi mdi-newspaper',
        ]);

        $menu_instance->add_menu_item('seeder-page-builder-donation-home-settings', [
            'route' => 'landlord.admin.seeder.page.builder.index',
            'label' => __('Donation (Home Page)'),
            'parent' => 'home-pages-seeder-settings',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('seeder-page-builder-event-home-settings', [
            'route' => '#',
            'label' => __('Coming Soon...'),
            'parent' => 'home-pages-seeder-settings',
            'permissions' => [],
        ]);

    }
    private function seeder_settings_menus(MenuWithPermission $menu_instance) : void
    {
        $menu_instance->add_menu_item('seeder-settings', [
            'route' => '#',
            'label' => __('Demo Other Data Manage'),
            'parent' => null,
            'permissions' => [],
            'icon' => 'mdi mdi-newspaper',
        ]);

        $menu_instance->add_menu_item('seeder-pages-settings', [
            'route' => 'landlord.admin.seeder.pages.index',
            'label' => __('All Pages'),
            'parent' => 'seeder-settings',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('seeder-widget-settings', [
            'route' => 'landlord.admin.seeder.widget.index',
            'label' => __('All Widgets'),
            'parent' => 'seeder-settings',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('seeder-product-settings', [
            'route' => 'landlord.admin.seeder.product.index',
            'label' => __('All Products'),
            'parent' => 'seeder-settings',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('seeder-donation-settings', [
            'route' => 'landlord.admin.seeder.donation.index',
            'label' => __('All Donation'),
            'parent' => 'seeder-settings',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('seeder-donation-settings', [
            'route' => 'landlord.admin.seeder.appointment.index',
            'label' => __('All Appointment'),
            'parent' => 'seeder-settings',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('seeder-event-settings', [
            'route' => 'landlord.admin.seeder.event.index',
            'label' => __('All Events'),
            'parent' => 'seeder-settings',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('seeder-job-settings', [
            'route' => 'landlord.admin.seeder.job.index',
            'label' => __('All Jobs'),
            'parent' => 'seeder-settings',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('seeder-portfolio-settings', [
            'route' => 'landlord.admin.seeder.portfolio.index',
            'label' => __('All Portfolio'),
            'parent' => 'seeder-settings',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('seeder-blogs-settings', [
            'route' => 'landlord.admin.seeder.blog.index',
            'label' => __('All Blogs'),
            'parent' => 'seeder-settings',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('seeder-services-settings', [
            'route' => 'landlord.admin.seeder.service.index',
            'label' => __('All Services'),
            'parent' => 'seeder-settings',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('seeder-article-settings', [
            'route' => 'landlord.admin.seeder.article.index',
            'label' => __('All Knowledgebases'),
            'parent' => 'seeder-settings',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('seeder-testimonial-settings', [
            'route' => 'landlord.admin.seeder.testimonial.index',
            'label' => __('All Testimonial'),
            'parent' => 'seeder-settings',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('seeder-faq-settings', [
            'route' => 'landlord.admin.seeder.faq.index',
            'label' => __('All Faq'),
            'parent' => 'seeder-settings',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('seeder-price-plan-settings', [
            'route' => 'landlord.admin.seeder.price.plan.index',
            'label' => __('All Price Plan'),
            'parent' => 'seeder-settings',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('seeder-gallery-plan-settings', [
            'route' => 'landlord.admin.seeder.gallery.index',
            'label' => __('All Image Gallery'),
            'parent' => 'seeder-settings',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('seeder-language-settings', [
            'route' => 'landlord.admin.seeder.language.data.settings',
            'label' => __('All Language'),
            'parent' => 'seeder-settings',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('seeder-tenant-demo-data-settings', [
            'route' => 'landlord.admin.seeder.demo.data.settings',
            'label' => __('All Tenant Demo Data'),
            'parent' => 'seeder-settings',
            'permissions' => [],
        ]);

    }



    private function newsletter_settings_menus(MenuWithPermission $menu_instance) : void
    {
        $menu_instance->add_menu_item('newsletter', [
            'route' => '#',
            'label' => __('Newsletter Manage'),
            'parent' => null,
            'permissions' => ['newsletter-list','newsletter-create','newsletter-edit','newsletter-delete'],
            'icon' => 'mdi mdi-newspaper',
        ]);

        $menu_instance->add_menu_item('all-newsletter', [
            'route' => 'landlord.admin.newsletter',
            'label' => __('All Subscribers'),
            'parent' => 'newsletter',
            'permissions' => ['newsletter-list'],
        ]);

        $menu_instance->add_menu_item('mail-send-all-newsletter', [
            'route' => 'landlord.admin.newsletter.mail',
            'label' => __('Send Mail to All'),
            'parent' => 'newsletter',
            'permissions' => [],
        ]);
    }

    private function order_manage_settings_menus(MenuWithPermission $menu_instance) : void
    {
        $menu_instance->add_menu_item('order-manage-settings-menu-items', [
            'route' => '#',
            'label' => __('Package Order Manage'),
            'parent' => null,
            'permissions' => ['package-order-all-order','package-order-pending-order','package-order-pending-order',
                'package-order-progress-order','package-order-complete-order','package-order-success-order-page','package-order-cancel-order-page',
                'package-order-order-page-manage','package-order-order-report','package-order-payment-logs','package-order-payment-report'
            ],
            'icon' => 'mdi mdi-cash-multiple',
        ]);
        $menu_instance->add_menu_item('order-manage-all-order-settings-all-page-settings', [
            'route' => 'landlord.admin.package.order.manage.all',
            'label' => __('All Order'),
            'parent' => 'order-manage-settings-menu-items',
            'permissions' => ['package-order-all-order'],
        ]);
        $menu_instance->add_menu_item('order-manage-pending-order-settings-new-page-settings', [
            'route' => 'landlord.admin.package.order.manage.pending',
            'label' => __('Pending Order'),
            'parent' => 'order-manage-settings-menu-items',
            'permissions' => ['package-order-pending-order'],
        ]);
        $menu_instance->add_menu_item('order-manage-in-progress-settings-new-page-settings', [
            'route' => 'landlord.admin.package.order.manage.in.progress',
            'label' => __('In Progress Order'),
            'parent' => 'order-manage-settings-menu-items',
            'permissions' => ['package-order-progress-order'],
        ]);
        $menu_instance->add_menu_item('order-manage-complete-order-settings-new-page-settings', [
            'route' => 'landlord.admin.package.order.manage.completed',
            'label' => __('Completed Order'),
            'parent' => 'order-manage-settings-menu-items',
            'permissions' => ['package-order-complete-order'],
        ]);
        $menu_instance->add_menu_item('order-manage-success-page-settings-new-page-settings', [
            'route' => 'landlord.admin.package.order.success.page',
            'label' => __('Success Order Page'),
            'parent' => 'order-manage-settings-menu-items',
            'permissions' => [ 'package-order-success-order-page'],
        ]);
        $menu_instance->add_menu_item('order-manage-cancel-page-settings-new-page-settings', [
            'route' => 'landlord.admin.package.order.cancel.page',
            'label' => __('Cancel Order Page'),
            'parent' => 'order-manage-settings-menu-items',
            'permissions' => ['package-order-cancel-order-page'],
        ]);

        $menu_instance->add_menu_item('order-manage-order-report-settings-new-page-settings', [
            'route' => 'landlord.admin.package.order.report',
            'label' => __('Order Report'),
            'parent' => 'order-manage-settings-menu-items',
            'permissions' => [ 'package-order-order-report'],
        ]);
        $menu_instance->add_menu_item('order-manage-payment-log-settings-new-page-settings', [
            'route' => 'landlord.admin.payment.logs',
            'label' => __('All Payment Logs'),
            'parent' => 'order-manage-settings-menu-items',
            'permissions' => ['package-order-payment-logs'],
        ]);
        $menu_instance->add_menu_item('order-manage-payment-report-settings-new-page-settings', [
            'route' => 'landlord.admin.payment.report',
            'label' => __('Payment Report'),
            'parent' => 'order-manage-settings-menu-items',
            'permissions' => ['package-order-payment-report'],
        ]);
    }

    private function custom_domain_settings_menus(MenuWithPermission $menu_instance) : void
    {
        $menu_instance->add_menu_item('custom-domain-settings-menu-items', [
            'route' => '#',
            'label' => __('Custom Domain'),
            'parent' => null,
            'permissions' => ['custom-domain'],
            'icon' => 'mdi mdi-cash-multiple',
        ]);
        $menu_instance->add_menu_item('all-pending-custom-domain-request', [
            'route' => 'landlord.admin.custom.domain.requests.all.pending',
            'label' => __('All Pending Request'),
            'parent' => 'custom-domain-settings-menu-items',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('all-custom-domain-request', [
            'route' => 'landlord.admin.custom.domain.requests.all',
            'label' => __('All Requests'),
            'parent' => 'custom-domain-settings-menu-items',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('all-custom-domain-request-settings', [
            'route' => 'landlord.admin.custom.domain.requests.settings',
            'label' => __('Settings'),
            'parent' => 'custom-domain-settings-menu-items',
            'permissions' => [],
        ]);

    }

    private function testimonial_settings_menus(MenuWithPermission $menu_instance) : void
    {
        $menu_instance->add_menu_item('testimonial', [
            'route' => 'landlord.admin.testimonial',
            'label' => __('Testimonial'),
            'parent' => null,
            'permissions' => ['testimonial-list', 'testimonial-create','testimonial-edit','testimonial-delete'],
            'icon' => 'mdi mdi-format-quote-close',
        ]);
    }


    private function blog_settings_menus(MenuWithPermission $menu_instance) : void
    {
        $menu_instance->add_menu_item('blog-settings-menu-items', [
            'route' => '#',
            'label' => __('Blogs'),
            'parent' => null,
            'permissions' => ['blog-list','blog-create','blog-edit','blog-delete','blog-settings'],
            'icon' => 'mdi mdi-blogger',
        ]);

        $menu_instance->add_menu_item('blog-all-settings-menu-items', [
            'route' => 'landlord.admin.blog',
            'label' => __('All Blogs'),
            'parent' => 'blog-settings-menu-items',
            'permissions' => ['blog-list'],
        ]);

        $menu_instance->add_menu_item('blog-add-settings-menu-items', [
            'route' => 'landlord.admin.blog.new',
            'label' => __('Add New Blog'),
            'parent' => 'blog-settings-menu-items',
            'permissions' => ['blog-create'],
        ]);

        $menu_instance->add_menu_item('blog-category-settings-all', [
            'route' => 'landlord.admin.blog.category',
            'label' => __('Blog Category'),
            'parent' => 'blog-settings-menu-items',
            'permissions' => ['blog-category-list'],
        ]);

        $menu_instance->add_menu_item('blog-settings-all', [
            'route' => 'landlord.admin.blog.settings',
            'label' => __('Settings'),
            'parent' => 'blog-settings-menu-items',
            'permissions' => ['blog-settings'],
        ]);

    }

    private function support_ticket_settings_menus(MenuWithPermission $menu_instance) : void
    {
        $menu_instance->add_menu_item('support-tickets-settings-menu-items', [
            'route' => '#',
            'label' => __('Support Tickets'),
            'parent' => null,
            'permissions' => ['support-ticket-list','support-ticket-create','support-ticket-edit','support-ticket-delete',],
            'icon' => 'mdi mdi-folder-outline',
        ]);

        $menu_instance->add_menu_item('support-ticket-settings-all', [
            'route' => 'landlord.admin.support.ticket.all',
            'label' => __('All Tickets'),
            'parent' => 'support-tickets-settings-menu-items',
            'permissions' => ['support-ticket-list'],
        ]);

        $menu_instance->add_menu_item('support-ticket-settings-add', [
            'route' => 'landlord.admin.support.ticket.new',
            'label' => __('Add New Ticket'),
            'parent' => 'support-tickets-settings-menu-items',
            'permissions' => ['support-ticket-create'],
        ]);

        $menu_instance->add_menu_item('support-ticket-settings-department', [
            'route' => 'landlord.admin.support.ticket.department',
            'label' => __('Departments'),
            'parent' => 'support-tickets-settings-menu-items',
            'permissions' => ['support-ticket-department-list','support-ticket-department-create','support-ticket-department-edit','support-ticket-department-delete',],
        ]);

        $menu_instance->add_menu_item('support-ticket-settings-setting', [
            'route' => 'landlord.admin.support.ticket.page.settings',
            'label' => __('Page Settings'),
            'parent' => 'support-tickets-settings-menu-items',
            'permissions' => [],
        ]);
    }

    private function all_notification_settings_menus(MenuWithPermission $menu_instance) : void
    {
        $menu_instance->add_menu_item('notifications', [
            'route' => 'landlord.admin.notification.all',
            'label' => __('Notifications'),
            'parent' => null,
            'permissions' => [ 'notification-list'],
            'icon' => 'mdi mdi-bell',
        ]);

        $menu_instance->add_menu_item('site-notifications', [
            'route' => 'landlord.admin.notification.all',
            'label' => __('All Notifications'),
            'parent' => 'notifications',
            'permissions' => [ 'notification-list'],
            'icon' => '',
        ]);

        $menu_instance->add_menu_item('users-manage-settings-activity-log', [
            'route' => 'landlord.admin.tenant.activity.log',
            'label' => __('User Activity Log'),
            'parent' => 'notifications',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('users-manage-settings-cronjob-log', [
            'route' => 'landlord.admin.tenant.cronjob.log',
            'label' => __('Cronjob Log'),
            'parent' => 'notifications',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('form-builder-settings-contact-message', [
            'route' => 'landlord.admin.contact.message.all',
            'label' => __('All Contact Messages'),
            'parent' => 'notifications',
            'permissions' => ['form-builder'],
        ]);
    }

    private function brands_settings_menus(MenuWithPermission $menu_instance) : void
    {
        $menu_instance->add_menu_item('brands', [
            'route' => 'landlord.admin.brands',
            'label' => __('Brands'),
            'parent' => null,
            'permissions' => [ 'brand-list','brand-create','brand-edit','brand-delete'],
            'icon' => 'mdi mdi-slack',
        ]);
    }

    private function coupon_settings_menus(MenuWithPermission $menu_instance) : void
    {
        $menu_instance->add_menu_item('coupons', [
            'route' => 'landlord.admin.coupons',
            'label' => __('Coupons'),
            'parent' => null,
            'permissions' => [ 'coupon-list','coupon-create','coupon-edit','coupon-delete'],
            'icon' => 'mdi mdi-file',
        ]);
    }


    private function form_builder_settings_menus(MenuWithPermission $menu_instance) : void
    {
        $menu_instance->add_menu_item('form-builder-settings-menu-items', [
            'route' => '#',
            'label' => __('Form Builder'),
            'parent' => null,
            'permissions' => ['form-builder'],
            'icon' => 'mdi mdi-folder-outline',
        ]);

        $menu_instance->add_menu_item('form-builder-settings-all', [
            'route' => 'landlord.admin.form.builder.all',
            'label' => __('Custom From Builder'),
            'parent' => 'form-builder-settings-menu-items',
            'permissions' => ['form-builder'],
        ]);

    }

    private function appearance_settings_menus(MenuWithPermission $menu_instance) : void
    {
        $menu_instance->add_menu_item('appearance-settings-menu-items', [
            'route' => '#',
            'label' => __('Appearance Settings'),
            'parent' => null,
            'permissions' => ['appearance-widget-builder','appearance-menu-manage','appearance-country-list','appearance-404-settings'
                ,'appearance-topbar-settings','appearance-email-template','appearance-login-register-settings','appearance-maintenance-settings'],
            'icon' => 'mdi mdi-folder-outline',
        ]);

        $menu_instance->add_menu_item('widget-builder-settings-all', [
            'route' => 'landlord.admin.widgets',
            'label' => __('Widget Builder'),
            'parent' => 'appearance-settings-menu-items',
            'permissions' => ['appearance-widget-builder'],
        ]);

        $menu_instance->add_menu_item('menu-settings-all', [
            'route' => 'landlord.admin.menu',
            'label' => __('Menu Manage'),
            'parent' => 'appearance-settings-menu-items',
            'permissions' => ['appearance-menu-manage'],
        ]);

        $menu_instance->add_menu_item('country-all-landlord-settings-menu-items', [
            'route' => 'landlord.admin.country.all',
            'label' => __('Country Manage'),
            'parent' => 'appearance-settings-menu-items',
            'permissions' => ['appearance-country-list'],
        ]);

        $menu_instance->add_menu_item('404-settings-all', [
            'route' => 'landlord.admin.404.page.settings',
            'label' => __('404 Settings'),
            'parent' => 'appearance-settings-menu-items',
            'permissions' => ['appearance-404-settings'],
        ]);

        $menu_instance->add_menu_item('topbar-settings-all', [
            'route' => 'landlord.admin.topbar.settings',
            'label' => __('Topbar Settings'),
            'parent' => 'appearance-settings-menu-items',
            'permissions' => ['appearance-topbar-settings'],
        ]);

        $menu_instance->add_menu_item('email-template-settings-all', [
            'route' => 'landlord.admin.email.template.all',
            'label' => __('Email Template Settings'),
            'parent' => 'appearance-settings-menu-items',
            'permissions' => ['appearance-email-template'],
        ]);

        $menu_instance->add_menu_item('login-register-settings-all', [
            'route' => 'landlord.admin.login.register.settings',
            'label' => __('Login/Register Settings'),
            'parent' => 'appearance-settings-menu-items',
            'permissions' => ['appearance-login-register-settings'],
        ]);

        $menu_instance->add_menu_item('maintenance-settings-all', [
            'route' => 'landlord.admin.maintains.page.settings',
            'label' => __('Maintenance Settings'),
            'parent' => 'appearance-settings-menu-items',
            'permissions' => ['appearance-maintenance-settings'],
        ]);



    }

    private function general_settings_menus(MenuWithPermission $menu_instance) : void
    {
        $menu_instance->add_menu_item('general-settings-menu-items', [
            'route' => '#',
            'label' => __('General Settings'),
            'parent' => null,
            'permissions' => ['general-settings-page-settings','general-settings-site-identity','general-settings-basic-settings','general-settings-color-settings',
                'general-settings-typography-settings','general-settings-seo-settings','general-settings-payment-settings','general-settings-third-party-script-settings',
                'general-settings-smtp-settings','general-settings-custom-css-settings','general-settings-custom-js-settings','general-settings-database-upgrade-settings',
                'general-settings-cache-clear-settings','general-settings-license-settings'],
            'icon' => 'mdi mdi-settings',
        ]);
        $menu_instance->add_menu_item('general-settings-page-settings', [
            'route' => 'landlord.admin.general.page.settings',
            'label' => __('Page Settings'),
            'parent' => 'general-settings-menu-items',
            'permissions' => ['general-settings-page-settings'],
        ]);
        $menu_instance->add_menu_item('general-settings-application-settings', [
            'route' => 'landlord.admin.general.application.settings',
            'label' => __('Application Settings'),
            'parent' => 'general-settings-menu-items',
            'permissions' => ['general-settings-application-settings'],
        ]);
        $menu_instance->add_menu_item('general-settings-site-identity', [
            'route' => 'landlord.admin.general.site.identity',
            'label' => __('Site Identity'),
            'parent' => 'general-settings-menu-items',
            'permissions' => ['general-settings-site-identity'],
        ]);
        $menu_instance->add_menu_item('general-settings-basic-settings', [
            'route' => 'landlord.admin.general.basic.settings',
            'label' => __('Basic Settings'),
            'parent' => 'general-settings-menu-items',
            'permissions' => ['general-settings-basic-settings'],
        ]);
        $menu_instance->add_menu_item('general-settings-color-settings', [
            'route' => 'landlord.admin.general.color.settings',
            'label' => __('Color Settings'),
            'parent' => 'general-settings-menu-items',
            'permissions' => ['general-settings-color-settings'],
        ]);
        $menu_instance->add_menu_item('general-settings-typography-settings', [
            'route' => 'landlord.admin.general.typography.settings',
            'label' => __('Typography Settings'),
            'parent' => 'general-settings-menu-items',
            'permissions' => ['general-settings-typography-settings'],
        ]);
        $menu_instance->add_menu_item('general-settings-seo-settings', [
            'route' => 'landlord.admin.general.seo.settings',
            'label' => __('SEO Settings'),
            'parent' => 'general-settings-menu-items',
            'permissions' => ['general-settings-seo-settings'],
        ]);

        $menu_instance->add_menu_item('general-settings-third-party-script-settings', [
            'route' => 'landlord.admin.general.third.party.script.settings',
            'label' => __('Third Party Script'),
            'parent' => 'general-settings-menu-items',
            'permissions' => ['general-settings-third-party-script-settings'],
        ]);
        $menu_instance->add_menu_item('general-settings-smtp-settings', [
            'route' => 'landlord.admin.general.smtp.settings',
            'label' => __('Smtp Settings'),
            'parent' => 'general-settings-menu-items',
            'permissions' => ['general-settings-smtp-settings'],
        ]);
        $menu_instance->add_menu_item('general-settings-custom-css-settings', [
            'route' => 'landlord.admin.general.custom.css.settings',
            'label' => __('Custom CSS'),
            'parent' => 'general-settings-menu-items',
            'permissions' => ['general-settings-custom-css-settings'],
        ]);
        $menu_instance->add_menu_item('general-settings-custom-js-settings', [
            'route' => 'landlord.admin.general.custom.js.settings',
            'label' => __('Custom JS'),
            'parent' => 'general-settings-menu-items',
            'permissions' => ['general-settings-custom-js-settings'],
        ]);
        $menu_instance->add_menu_item('general-settings-database-upgrade-settings', [
            'route' => 'landlord.admin.general.database.upgrade.settings',
            'label' => __('Database Upgrade'),
            'parent' => 'general-settings-menu-items',
            'permissions' => ['general-settings-database-upgrade-settings'],
        ]);
        $menu_instance->add_menu_item('general-settings-cache-settings', [
            'route' => 'landlord.admin.general.cache.settings',
            'label' => __('Cache Settings'),
            'parent' => 'general-settings-menu-items',
            'permissions' => ['general-settings-cache-clear-settings'],
        ]);
        $menu_instance->add_menu_item('general-settings-gdpr-settings', [
            'route' => 'landlord.admin.general.gdpr.settings',
            'label' => __('GDPR Settings'),
            'parent' => 'general-settings-menu-items',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('general-settings-sitemap-settings', [
            'route' => 'landlord.admin.general.sitemap.settings',
            'label' => __('Sitemap Settings'),
            'parent' => 'general-settings-menu-items',
            'permissions' => [],
        ]);
        $menu_instance->add_menu_item('general-settings-license-settings', [
            'route' => 'landlord.admin.general.license.settings',
            'label' => __('License Settings'),
            'parent' => 'general-settings-menu-items',
            'permissions' => ['general-settings-license-settings'],
        ]);
        $menu_instance->add_menu_item('general-settings-check-update-settings', [
            'route' => 'landlord.admin.general.software.update.settings',
            'label' => __('Check Update'),
            'parent' => 'general-settings-menu-items',
            'permissions' => ['general-settings-license-settings'],
        ]);
    }

    private function users_manage_menus(MenuWithPermission $menu_instance) : void
    {
        $menu_instance->add_menu_item('users-manage-settings-menu-items', [
            'route' => '#',
            'label' => __('Users Manage'),
            'parent' => null,
            'permissions' => [],
            'icon' => 'mdi mdi-account-multiple',
        ]);
        $menu_instance->add_menu_item('users-manage-settings-list-menu-items', [
            'route' => 'landlord.admin.tenant',
            'label' => __('All Users'),
            'parent' => 'users-manage-settings-menu-items',
            'permissions' => [],
        ]);
        $menu_instance->add_menu_item('users-manage-settings-add-new-menu-items', [
            'route' => 'landlord.admin.tenant.new',
            'label' => __('Add New'),
            'parent' => 'users-manage-settings-menu-items',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('users-manage-settings', [
            'route' => 'landlord.admin.tenant.settings',
            'label' => __('Account Settings'),
            'parent' => 'users-manage-settings-menu-items',
            'permissions' => [],
        ]);
    }

    private function users_website_issues_manage_menus(MenuWithPermission $menu_instance) : void
    {
        $menu_instance->add_menu_item('users-website-issues-manage-settings-menu-items', [
            'route' => '#',
            'label' => __('User Website'),
            'parent' => null,
            'permissions' => ['user-website-issue-list'],
            'icon' => 'mdi mdi-account-multiple',
        ]);
        $menu_instance->add_menu_item('users-issues-manage-settings-list-menu-items', [
            'route' => 'landlord.admin.tenant.website.issues',
            'label' => __('User website Issues'),
            'parent' => 'users-website-issues-manage-settings-menu-items',
            'permissions' => ['user-website-issue-list'],
        ]);

        $menu_instance->add_menu_item('users-website-manage-settings-list-menu-items', [
            'route' => 'landlord.admin.tenant.website.list',
            'label' => __('All User website List'),
            'parent' => 'users-website-issues-manage-settings-menu-items',
            'permissions' => ['user-website-issue-list'],
        ]);

        $menu_instance->add_menu_item('users-website-instruction-settings-list-menu-items', [
            'route' => 'landlord.admin.tenant.website.instruction.all',
            'label' => __('Website Instruction'),
            'parent' => 'users-website-issues-manage-settings-menu-items',
            'permissions' => [],
        ]);

    }

    private function payment_gateway_manage_menus(MenuWithPermission $menu_instance) : void
    {
        $menu_instance->add_menu_item('payment_gateway-manage-settings-menu-items', [
            'route' => '#',
            'label' => __('Payment Settings'),
            'parent' => null,
            'permissions' => ['user-website-issue-list'],
            'icon' => 'mdi mdi-settings',
        ]);

        $menu_instance->add_menu_item('currency-settings-list-menu-items', [
            'route' => 'landlord.admin.payment.currency.settings',
            'label' => __('Currencies'),
            'parent' => 'payment_gateway-manage-settings-menu-items',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('paypal-settings-list-menu-items', [
            'route' => 'landlord.admin.payment.paypal.settings',
            'label' => __('Paypal'),
            'parent' => 'payment_gateway-manage-settings-menu-items',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('paytm-settings-list-menu-items', [
            'route' => 'landlord.admin.payment.paytm.settings',
            'label' => __('Paytm'),
            'parent' => 'payment_gateway-manage-settings-menu-items',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('stripe-settings-list-menu-items', [
            'route' => 'landlord.admin.payment.stripe.settings',
            'label' => __('Stripe'),
            'parent' => 'payment_gateway-manage-settings-menu-items',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('razorpay-settings-list-menu-items', [
            'route' => 'landlord.admin.payment.razorpay.settings',
            'label' => __('Razorpay'),
            'parent' => 'payment_gateway-manage-settings-menu-items',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('paystack-settings-list-menu-items', [
            'route' => 'landlord.admin.payment.paystack.settings',
            'label' => __('Paystack'),
            'parent' => 'payment_gateway-manage-settings-menu-items',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('mollie-settings-list-menu-items', [
            'route' => 'landlord.admin.payment.mollie.settings',
            'label' => __('Mollie'),
            'parent' => 'payment_gateway-manage-settings-menu-items',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('payfast-settings-list-menu-items', [
            'route' => 'landlord.admin.payment.payfast.settings',
            'label' => __('Payfast'),
            'parent' => 'payment_gateway-manage-settings-menu-items',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('midtrans-settings-list-menu-items', [
            'route' => 'landlord.admin.payment.midtrans.settings',
            'label' => __('Midtrans'),
            'parent' => 'payment_gateway-manage-settings-menu-items',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('cashfree-settings-list-menu-items', [
            'route' => 'landlord.admin.payment.cashfree.settings',
            'label' => __('Cashfree'),
            'parent' => 'payment_gateway-manage-settings-menu-items',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('instamojo-settings-list-menu-items', [
            'route' => 'landlord.admin.payment.instamojo.settings',
            'label' => __('Instamojo'),
            'parent' => 'payment_gateway-manage-settings-menu-items',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('marcadopago-settings-list-menu-items', [
            'route' => 'landlord.admin.payment.marcadopago.settings',
            'label' => __('Marcadopago'),
            'parent' => 'payment_gateway-manage-settings-menu-items',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('zitopay-settings-list-menu-items', [
            'route' => 'landlord.admin.payment.zitopay.settings',
            'label' => __('Zitopay'),
            'parent' => 'payment_gateway-manage-settings-menu-items',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('squareup-settings-list-menu-items', [
            'route' => 'landlord.admin.payment.squareup.settings',
            'label' => __('Squareup'),
            'parent' => 'payment_gateway-manage-settings-menu-items',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('cinetpay-settings-list-menu-items', [
            'route' => 'landlord.admin.payment.cinetpay.settings',
            'label' => __('Cinetpay'),
            'parent' => 'payment_gateway-manage-settings-menu-items',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('paytabs-settings-list-menu-items', [
            'route' => 'landlord.admin.payment.paytabs.settings',
            'label' => __('Paytabs'),
            'parent' => 'payment_gateway-manage-settings-menu-items',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('billplz-settings-list-menu-items', [
            'route' => 'landlord.admin.payment.billplz.settings',
            'label' => __('Billplz'),
            'parent' => 'payment_gateway-manage-settings-menu-items',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('manual_payment-settings-list-menu-items', [
            'route' => 'landlord.admin.payment.manual_payment.settings',
            'label' => __('Manual Payment'),
            'parent' => 'payment_gateway-manage-settings-menu-items',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('bank-transfer-settings-list-menu-items', [
            'route' => 'landlord.admin.payment.bank_transfer.settings',
            'label' => __('Bank Transfer'),
            'parent' => 'payment_gateway-manage-settings-menu-items',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('flutterwave-settings-list-menu-items', [
            'route' => 'landlord.admin.payment.flutterwave.settings',
            'label' => __('Flutterwave'),
            'parent' => 'payment_gateway-manage-settings-menu-items',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('toyyibpay-settings-list-menu-items', [
            'route' => 'landlord.admin.payment.toyyibpay.settings',
            'label' => __('Toyyibpay'),
            'parent' => 'payment_gateway-manage-settings-menu-items',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('pagali-settings-list-menu-items', [
            'route' => 'landlord.admin.payment.pagali.settings',
            'label' => __('Pagali'),
            'parent' => 'payment_gateway-manage-settings-menu-items',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('authorizenet-settings-list-menu-items', [
            'route' => 'landlord.admin.payment.authorizenet.settings',
            'label' => __('Authorizenet'),
            'parent' => 'payment_gateway-manage-settings-menu-items',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('sitesway-settings-list-menu-items', [
            'route' => 'landlord.admin.payment.sitesway.settings',
            'label' => __('Sitesway'),
            'parent' => 'payment_gateway-manage-settings-menu-items',
            'permissions' => [],
        ]);
        $menu_instance->add_menu_item('kinetic-settings-list-menu-items', [
            'route' => 'landlord.admin.payment.kinetic.settings',
            'label' => __('kinetic'),
            'parent' => 'payment_gateway-manage-settings-menu-items',
            'permissions' => [],
        ]);

    }

    private function admin_manage_menus(MenuWithPermission $menu_instance) : void
    {
        $menu_instance->add_menu_item('admin-manage-settings-menu-items', [
            'route' => '#',
            'label' => __('Admin Role Manage'),
            'parent' => null,
            'permissions' => [],
            'icon' => 'mdi mdi-account-multiple',
        ]);
        $menu_instance->add_menu_item('admins-manage-settings-list-menu-items', [
            'route' => 'landlord.admin.all.user',
            'label' => __('All Admin'),
            'parent' => 'admin-manage-settings-menu-items',
            'permissions' => [],
        ]);
        $menu_instance->add_menu_item('admins-manage-settings-add-new-menu-items', [
            'route' => 'landlord.admin.new.user',
            'label' => __('Add New Admin'),
            'parent' => 'admin-manage-settings-menu-items',
            'permissions' => [],
        ]);
        $menu_instance->add_menu_item('admins-role-manage-settings-add-new-menu-items', [
            'route' => 'landlord.admin.all.admin.role',
            'label' => __('All Admin Role'),
            'parent' => 'admin-manage-settings-menu-items',
            'permissions' => [],
        ]);
    }


    /* tenent menu */
    public function render_tenant_sidebar_menus() : string
    {
        $menu_instance = new \App\Helpers\MenuWithPermission();
        $admin = \Auth::guard('admin')->user();

        $current_tenant_payment_data = tenant()->payment_log()?->first() ?? null;

        if(is_null($current_tenant_payment_data)){
            return '';
        }

        $current_tenant_payment_data = $current_tenant_payment_data->payment_status == 'complete' || $current_tenant_payment_data->payment_status == 'pending'
        && $current_tenant_payment_data->status == 'trial' ? $current_tenant_payment_data : [];

        if(!empty($current_tenant_payment_data)) {
            $package = $current_tenant_payment_data->package ?? [];

            if (!empty($package)) {
                $all_features = $package->plan_features ?? [];

                //for all check
                $check_all_feature = $all_features->pluck('feature_name')->toArray();


                if (in_array('dashboard',$check_all_feature)) {
                    $menu_instance->add_menu_item('tenant-dashboard-menu', [
                        'route' => 'tenant.admin.dashboard',
                        'label' => __('Dashboard'),
                        'parent' => null,
                        'permissions' => ['dashboard'],
                        'icon' => 'mdi mdi-home',
                    ]);
                }


                if (in_array('admin',$check_all_feature)) {
                    if ($admin->hasRole('Super Admin')) {
                        $this->tenant_admin_manage_menus($menu_instance);
                    }
                }


                if (in_array('user',$check_all_feature)) {
                    if ($admin->hasRole('Super Admin')) {
                        $this->tenant_users_manage_menus($menu_instance);
                    }
                }

                //Ecommerce
                $check_ecommerce_feature = $all_features->pluck('feature_name')->toArray();


                if (in_array('eCommerce',$check_ecommerce_feature)) {
                    if (isPluginActive('Product'))
                    {
                        $this->tenant_order_manage_settings_menus($menu_instance);
                    }

                    if (isPluginActive('Badge'))
                    {
                        $this->tenant_badge_settings_menus($menu_instance);
                    }

                    $this->tenant_country_settings_menus($menu_instance);
                    $this->tenant_tax_settings_menus($menu_instance);

                    if (isPluginActive('ShippingModule')) {
                        $this->tenant_shipping_settings_menus($menu_instance);
                    }

                    if (isPluginActive('CouponManage'))
                    {
                        $this->tenant_coupon_settings_menus($menu_instance);
                    }

                    if (isPluginActive('Attributes'))
                    {
                        $this->tenant_attribute_settings_menus($menu_instance);
                    }

                    if (isPluginActive('Product'))
                    {
                        if (in_array('product',$check_ecommerce_feature)) {
                            $this->tenant_product_settings_menus($menu_instance);
                        }
                    }

                    if (isPluginActive('Inventory'))
                    {
                        if (in_array('inventory',$check_ecommerce_feature)) {
                            $this->tenant_inventory_settings_menus($menu_instance);
                        }
                    }

                    if (isPluginActive('Campaign'))
                    {
                        if (in_array('campaign',$check_ecommerce_feature)) {
                            $this->tenant_campaign_settings_menus($menu_instance);
                        }
                    }
                }
                //Ecommerce


                if (isPluginActive('Donation')) {
                    if (in_array('donation', $check_all_feature)) {
                        $this->tenant_donation_settings_menus($menu_instance);
                        $this->tenant_donation_activities($menu_instance);
                    }
                }

                if (isPluginActive('Event')) {
                    if (in_array('event', $check_all_feature)) {
                        $this->tenant_event_settings_menus($menu_instance);
                    }
                }



                if (isPluginActive('Job')) {
                    if (in_array('job', $check_all_feature)) {
                        $this->tenant_job_settings_menus($menu_instance);
                    }
                }

                if (isPluginActive('Appointment')) {
                    if (in_array('appointment', $check_all_feature)) {
                        $this->tenant_appointment_settings_menus($menu_instance);
                    }
                }

                if (isPluginActive('SupportTicket')) {
                    if (in_array('support_ticket', $check_all_feature)) {
                        $this->tenant_support_ticket_settings_menus($menu_instance);
                    }
                }


                if (in_array('brand',$check_all_feature)) {
                    $this->tenant_brands_settings_menus($menu_instance);
                }


                if (in_array('custom_domain',$check_all_feature)) {
                    $this->tenant_custom_domain_request_settings_menus($menu_instance);
                }


                if (in_array('testimonial',$check_all_feature)) {
                    $this->tenant_testimonial_settings_menus($menu_instance);
                }


                if (in_array('form_builder',$check_all_feature)) {
                    $this->tenant_form_builder_settings_menus($menu_instance);
                }


                if (in_array('own_order_manage',$check_all_feature)) {
                    if ($admin->hasRole('Super Admin')) {
                        $this->tenant_payment_manage_menus($menu_instance);
                    }
                }

                if (in_array('page',$check_all_feature)) {
                    $this->tenant_pages_settings_menus($menu_instance);
                }


                if (isPluginActive('Portfolio')) {
                    if (in_array('portfolio',$check_all_feature)) {
                        $this->tenant_porfolio_settings_menu($menu_instance);
                    }
                }

                if (in_array('blog',$check_all_feature)) {
                    $this->tenant_blog_settings_menus($menu_instance);
                }

                if (in_array('advertisement',$check_all_feature)) {
                    $this->tenant_advertisement_settings_menus($menu_instance);
                }

                if (isPluginActive('Service')) {
                    if (in_array('service', $check_all_feature)) {
                        $this->tenant_services_settings_menus($menu_instance);
                    }
                }

                if (isPluginActive('Knowledgebase')) {
                    if (in_array('knowledgebase', $check_all_feature)) {
                        $this->tenant_knowledgebase_settings_menu($menu_instance);
                    }
                }

                if(isPluginActive('NewsLetter')){
                    if (in_array('newsletter',$check_all_feature)) {
                        $this->tenant_newsletter_settings_menus($menu_instance);
                    }
                }


                if (in_array('faq',$check_all_feature)) {
                    $this->tenant_faq_settings($menu_instance);
                }

                if (in_array('wedding_price_plan',$check_all_feature)) {
                    $this->tenant_wedding_price_plan_settings($menu_instance);
                }


                if (in_array('gallery',$check_all_feature)) {
                    $this->tenant_image_gallery($menu_instance);
                }

                // External Menu Render
                foreach (getAllExternalMenu() as $externalMenu)
                {
                    foreach ($externalMenu as $individual_menu_item){
                        $convert_to_array = (array) $individual_menu_item;
                        $routeName = $convert_to_array['route'];
                        if (isset($routeName) && !empty($routeName) && Route::has($routeName)){
                            $menu_instance->add_menu_item($convert_to_array['id'], $convert_to_array);
                        }
                    }
                }

                if (in_array('appearance_settings',$check_all_feature)) {
                    $this->tenant_appearance_settings_menus($menu_instance);
                }

                if (in_array('general_settings',$check_all_feature)) {
                    $this->tenant_general_settings_menus($menu_instance);
                }

                if (in_array('payment_gateways',$check_all_feature)) {
                    $this->tenant_payment_gateway_manage_menus($menu_instance);
                }



                if (in_array('language',$check_all_feature)) {
                    $menu_instance->add_menu_item('tenant-languages', [
                        'route' => 'tenant.admin.languages',
                        'label' => __('Languages'),
                        'parent' => null,
                        'permissions' => ['language-list', 'language-create', 'language-edit', 'language-delete'],
                        'icon' => 'mdi mdi-polymer ',
                    ]);
                }

            }
        }

        return $menu_instance->render_menu_items();
    }

    private function tenant_donation_settings_menus(MenuWithPermission $menu_instance): void
    {
        $menu_instance->add_menu_item('donation-settings-menu-items', [
            'route' => '#',
            'label' => __('Donations'),
            'parent' => null,
            'permissions' => ['donation-list','donation-create','donation-edit','donation-delete'],
            'icon' => 'mdi mdi-cash-multiple',
        ]);

        $menu_instance->add_menu_item('donation-settings-all-page-settings', [
            'route' => 'tenant.admin.donation',
            'label' => __('All Donations'),
            'parent' => 'donation-settings-menu-items',
            'permissions' => ['donation-list'],
        ]);

        $menu_instance->add_menu_item('donation-settings-add-page-settings', [
            'route' => 'tenant.admin.donation.new',
            'label' => __('Add Donation'),
            'parent' => 'donation-settings-menu-items',
            'permissions' => ['donation-create'],
        ]);

        $menu_instance->add_menu_item('donation-settings-category-page-settings', [
            'route' => 'tenant.admin.donation.category',
            'label' => __('Category'),
            'parent' => 'donation-settings-menu-items',
            'permissions' => ['donation-category'],
        ]);

        $menu_instance->add_menu_item('donation-settings-all-donations-logs', [
            'route' => 'tenant.admin.donation.payment.logs',
            'label' => __('All Payment Logs'),
            'parent' => 'donation-settings-menu-items',
            'permissions' => ['donation-payment'],
        ]);

        $menu_instance->add_menu_item('donation-settings-payment-logs-report', [
            'route' => 'tenant.admin.donation.payment.logs.report',
            'label' => __('Payment Logs Report'),
            'parent' => 'donation-settings-menu-items',
            'permissions' => ['donation-payment'],
        ]);

        $menu_instance->add_menu_item('donation-settings-donations-all-settings', [
            'route' => 'tenant.admin.donation.settings',
            'label' => __('Settings'),
            'parent' => 'donation-settings-menu-items',
            'permissions' => [],
        ]);
    }

    public function tenant_donation_activities(MenuWithPermission $menu_instance): void
    {
        $menu_instance->add_menu_item('donation-activities-settings-menu-items', [
            'route' => '#',
            'label' => __('Donation Activities'),
            'parent' => null,
            'permissions' => ['donation-activity-list'],
            'icon' => 'mdi mdi-cash-multiple',
        ]);

        $menu_instance->add_menu_item('settings-donation-activity-list', [
            'route' => 'tenant.admin.donation.activity',
            'label' => __('All Activity'),
            'parent' => 'donation-activities-settings-menu-items',
            'permissions' => ['donation-activity-list'],
        ]);

        $menu_instance->add_menu_item('donation-activity-add', [
            'route' => 'tenant.admin.donation.activity.new',
            'label' => __('Add Activity'),
            'parent' => 'donation-activities-settings-menu-items',
            'permissions' => ['donation-activity-create'],
        ]);

        $menu_instance->add_menu_item('settings-donation-activity-category', [
            'route' => 'tenant.admin.donation.activity.category',
            'label' => __('Category'),
            'parent' => 'donation-activities-settings-menu-items',
            'permissions' => ['donation-activity-create'],
        ]);
    }

    private function tenant_event_settings_menus(MenuWithPermission $menu_instance): void
    {
        $menu_instance->add_menu_item('event-settings-menu-items', [
            'route' => '#',
            'label' => __('Events'),
            'parent' => null,
            'permissions' => ['event-list'],
            'icon' => 'mdi mdi-cash-multiple',
        ]);

        $menu_instance->add_menu_item('event-settings-all-page-settings', [
            'route' => 'tenant.admin.event',
            'label' => __('All Event'),
            'parent' => 'event-settings-menu-items',
            'permissions' => ['event-list'],
        ]);

        $menu_instance->add_menu_item('event-settings-add-page-settings', [
            'route' => 'tenant.admin.event.new',
            'label' => __('Add Event'),
            'parent' => 'event-settings-menu-items',
            'permissions' => ['event-create'],
        ]);

        $menu_instance->add_menu_item('event-settings-category-page-settings', [
            'route' => 'tenant.admin.event.category',
            'label' => __('Category'),
            'parent' => 'event-settings-menu-items',
            'permissions' => ['event-create'],
        ]);

        $menu_instance->add_menu_item('event-settings-all-donations-logs', [
            'route' => 'tenant.admin.event.payment.logs',
            'label' => __('All Payment Logs'),
            'parent' => 'event-settings-menu-items',
            'permissions' => ['event-payment'],
        ]);

        $menu_instance->add_menu_item('event-settings-payment-logs-report', [
            'route' => 'tenant.admin.event.payment.logs.report',
            'label' => __('Payment Logs Report'),
            'parent' => 'event-settings-menu-items',
            'permissions' => ['event-payment'],
        ]);

        $menu_instance->add_menu_item('event-settings-event-all-settings', [
            'route' => 'tenant.admin.event.settings',
            'label' => __('Settings'),
            'parent' => 'event-settings-menu-items',
            'permissions' => [],
        ]);

    }

    private function tenant_job_settings_menus(MenuWithPermission $menu_instance): void
    {
        $menu_instance->add_menu_item('job-settings-menu-items', [
            'route' => '#',
            'label' => __('Jobs'),
            'parent' => null,
            'permissions' => ['job-list'],
            'icon' => 'mdi mdi-cash-multiple',
        ]);

        $menu_instance->add_menu_item('job-settings-all-page-settings', [
            'route' => 'tenant.admin.job',
            'label' => __('All Jobs'),
            'parent' => 'job-settings-menu-items',
            'permissions' => ['job-list'],
        ]);

        $menu_instance->add_menu_item('job-settings-add-page-settings', [
            'route' => 'tenant.admin.job.new',
            'label' => __('Add Job'),
            'parent' => 'job-settings-menu-items',
            'permissions' => ['job-create'],
        ]);

        $menu_instance->add_menu_item('job-settings-category-page-settings', [
            'route' => 'tenant.admin.job.category',
            'label' => __('Category'),
            'parent' => 'job-settings-menu-items',
            'permissions' => ['job-category'],
        ]);

        $menu_instance->add_menu_item('job-settings-all-paid-logs', [
            'route' => 'tenant.admin.job.paid.payment.logs',
            'label' => __('All Paid Applications'),
            'parent' => 'job-settings-menu-items',
            'permissions' => ['job-payment'],
        ]);

        $menu_instance->add_menu_item('job-settings-all-unpaid-logs', [
            'route' => 'tenant.admin.job.unpaid.payment.logs',
            'label' => __('All Unpaid Applications'),
            'parent' => 'job-settings-menu-items',
            'permissions' => ['job-payment'],
        ]);


        $menu_instance->add_menu_item('job-settings-payment-logs-report', [
            'route' => 'tenant.admin.job.payment.logs.report',
            'label' => __('Payment job Report'),
            'parent' => 'job-settings-menu-items',
            'permissions' => ['job-payment'],
        ]);

        $menu_instance->add_menu_item('job-settings-job-settings', [
            'route' => 'tenant.admin.job.settings',
            'label' => __('Settings'),
            'parent' => 'job-settings-menu-items',
            'permissions' => [],
        ]);


    }

    private function tenant_appointment_settings_menus(MenuWithPermission $menu_instance): void
    {
        $menu_instance->add_menu_item('appointment-settings-menu-items', [
            'route' => '#',
            'label' => __('Appointment'),
            'parent' => null,
            'permissions' => ['appointment-list','sub-appointment-list','appointment-category-list','appointment-sub-category-list','appointment-day-type',
                'appointment-days','appointment-schedule','appointment-payment-log','appointment-report','appointment-settings'],
            'icon' => 'mdi mdi-cash-multiple',
        ]);

        $menu_instance->add_menu_item('appointment-services-all-page-settings', [
            'route' => 'tenant.admin.appointment',
            'label' => __('All Appointments'),
            'parent' => 'appointment-settings-menu-items',
            'permissions' => ['appointment-list'],
        ]);

        $menu_instance->add_menu_item('appointment-categories-all-page-settings', [
            'route' => 'tenant.admin.appointment.category',
            'label' => __('Categories '),
            'parent' => 'appointment-settings-menu-items',
            'permissions' => ['appointment-category-list'],
        ]);

        $menu_instance->add_menu_item('appointment-sub-categories-all-page-settings', [
            'route' => 'tenant.admin.appointment.sub.category',
            'label' => __('Sub-categories '),
            'parent' => 'appointment-settings-menu-items',
            'permissions' => ['appointment-sub-category-list'],
        ]);


        $menu_instance->add_menu_item('appointment-sub-servicess-all-page-settings', [
            'route' => 'tenant.admin.sub.appointment',
            'label' => __('All Sub Appointments '),
            'parent' => 'appointment-settings-menu-items',
            'permissions' => ['sub-appointment-list'],
        ]);

        $menu_instance->add_menu_item('appointment-day-types-page-settings', [
            'route' => 'tenant.admin.appointment.day.types',
            'label' => __('Appointment Day Types'),
            'parent' => 'appointment-settings-menu-items',
            'permissions' => ['appointment-day-type'],
        ]);

        $menu_instance->add_menu_item('appointment-days-page-settings', [
            'route' => 'tenant.admin.appointment.days',
            'label' => __('Appointment Days'),
            'parent' => 'appointment-settings-menu-items',
            'permissions' => ['appointment-days'],
        ]);

        $menu_instance->add_menu_item('appointment-schedules-page-settings', [
            'route' => 'tenant.admin.appointment.schedule',
            'label' => __('Appointment Schedules'),
            'parent' => 'appointment-settings-menu-items',
            'permissions' => ['appointment-schedule'],
        ]);

        $menu_instance->add_menu_item('appointment-complete-logs', [
            'route' => 'tenant.admin.appointment.complete.payment.logs',
            'label' => __('All Payment Logs'),
            'parent' => 'appointment-settings-menu-items',
            'permissions' => ['appointment-payment-log'],
        ]);


        $menu_instance->add_menu_item('appointment-logs-report', [
            'route' => 'tenant.admin.appointment.payment.logs.report',
            'label' => __('Appointment Report'),
            'parent' => 'appointment-settings-menu-items',
            'permissions' => ['appointment-report'],
        ]);

        $menu_instance->add_menu_item('appointment-settings', [
            'route' => 'tenant.admin.appointment.settings',
            'label' => __('Settings'),
            'parent' => 'appointment-settings-menu-items',
            'permissions' => ['appointment-settings'],
        ]);

    }

    public function tenant_porfolio_settings_menu(MenuWithPermission $menu_instance): void
    {
        $menu_instance->add_menu_item('portfolio-settings-menu-items', [
            'route' => '#',
            'label' => __('Portfolio'),
            'parent' => null,
            'permissions' => ['portfolio-list'],
            'icon' => 'mdi mdi-cash-multiple',
        ]);

        $menu_instance->add_menu_item('settings-portfolio-list', [
            'route' => 'tenant.admin.portfolio',
            'label' => __('All Portfolio'),
            'parent' => 'portfolio-settings-menu-items',
            'permissions' => ['portfolio-list'],
        ]);

        $menu_instance->add_menu_item('settings-portfolio-add', [
            'route' => 'tenant.admin.portfolio.new',
            'label' => __('Add Portfolio'),
            'parent' => 'portfolio-settings-menu-items',
            'permissions' => ['portfolio-create'],
        ]);

        $menu_instance->add_menu_item('settings-portfolio-category', [
            'route' => 'tenant.admin.portfolio.category',
            'label' => __('Category'),
            'parent' => 'portfolio-settings-menu-items',
            'permissions' => ['portfolio-category'],
        ]);
    }

    public function tenant_knowledgebase_settings_menu(MenuWithPermission $menu_instance): void
    {
        $menu_instance->add_menu_item('knowledgebase-settings-menu-items', [
            'route' => '#',
            'label' => __('Knowledgebase'),
            'parent' => null,
            'permissions' => ['knowledgebase-list'],
            'icon' => 'mdi mdi-cash-multiple',
        ]);

        $menu_instance->add_menu_item('settings-knowledgebase-list', [
            'route' => 'tenant.admin.knowledgebase',
            'label' => __('All Knowledgebase'),
            'parent' => 'knowledgebase-settings-menu-items',
            'permissions' => ['knowledgebase-list'],
        ]);

        $menu_instance->add_menu_item('settings-knowledgebase-add', [
            'route' => 'tenant.admin.knowledgebase.new',
            'label' => __('Add Knowledgebase'),
            'parent' => 'knowledgebase-settings-menu-items',
            'permissions' => ['knowledgebase-create'],
        ]);

        $menu_instance->add_menu_item('settings-knowledgebase-category', [
            'route' => 'tenant.admin.knowledgebase.category',
            'label' => __('Category'),
            'parent' => 'knowledgebase-settings-menu-items',
            'permissions' => ['knowledgebase-category'],
        ]);
    }

    private function tenant_newsletter_settings_menus(MenuWithPermission $menu_instance) : void
    {
        $menu_instance->add_menu_item('newsletter', [
            'route' => '#',
            'label' => __('Newsletter Manage'),
            'parent' => null,
            'permissions' => ['newsletter-list','newsletter-create','newsletter-edit','newsletter-delete'],
            'icon' => 'mdi mdi-newspaper',
        ]);

        $menu_instance->add_menu_item('all-newsletter', [
            'route' => 'tenant.admin.newsletter',
            'label' => __('All Subscribers'),
            'parent' => 'newsletter',
            'permissions' => ['newsletter-list'],
        ]);

        $menu_instance->add_menu_item('mail-send-all-newsletter', [
            'route' => 'tenant.admin.newsletter.mail',
            'label' => __('Send Mail to All'),
            'parent' => 'newsletter',
            'permissions' => [],
        ]);
    }

    public function tenant_faq_settings(MenuWithPermission $menu_instance): void
    {
        $menu_instance->add_menu_item('faq-settings-menu-items', [
            'route' => '#',
            'label' => __('Faqs'),
            'parent' => null,
            'permissions' => ['faq-list'],
            'icon' => 'mdi mdi-cash-multiple',
        ]);

        $menu_instance->add_menu_item('fall-all-list', [
            'route' => 'tenant.admin.faq',
            'label' => __('All Faq'),
            'parent' => 'faq-settings-menu-items',
            'permissions' => ['faq-list'],
        ]);

        $menu_instance->add_menu_item('faq-category', [
            'route' => 'tenant.admin.faq.category',
            'label' => __('Category'),
            'parent' => 'faq-settings-menu-items',
            'permissions' => ['faq-category'],
        ]);
    }

    public function tenant_wedding_price_plan_settings(MenuWithPermission $menu_instance): void
    {
        $menu_instance->add_menu_item('wedding-price-plan-settings-menu-items', [
            'route' => '#',
            'label' => __('Price Plan'),
            'parent' => null,
            'permissions' => ['wedding-price-plan-list'],
            'icon' => 'mdi mdi-cash-multiple',
        ]);


        $menu_instance->add_menu_item('all-wedding-list', [
            'route' => 'tenant.admin.wedding.price.plan',
            'label' => __('All plans'),
            'parent' => 'wedding-price-plan-settings-menu-items',
            'permissions' => ['wedding-price-plan-list'],
        ]);

        $menu_instance->add_menu_item('all-wedding-plan-payment-logs', [
            'route' => 'tenant.admin.wedding.payment.logs',
            'label' => __('Payment Logs'),
            'parent' => 'wedding-price-plan-settings-menu-items',
            'permissions' => ['wedding-price-plan-payment-log-list'],
        ]);
    }

    public function tenant_image_gallery(MenuWithPermission $menu_instance): void
    {
        $menu_instance->add_menu_item('image_gallery-settings-menu-items', [
            'route' => '#',
            'label' => __('Image Gallery'),
            'parent' => null,
            'permissions' => ['image-gallery-list'],
            'icon' => 'mdi mdi-cash-multiple',
        ]);

        $menu_instance->add_menu_item('image-gallery-list', [
            'route' => 'tenant.admin.image.gallery',
            'label' => __('All Gallery'),
            'parent' => 'image_gallery-settings-menu-items',
            'permissions' => ['image-gallery-list'],
        ]);

        $menu_instance->add_menu_item('image-gallery-category', [
            'route' => 'tenant.admin.image.gallery.category',
            'label' => __('Category'),
            'parent' => 'image_gallery-settings-menu-items',
            'permissions' => ['image-gallery-category'],
        ]);
    }

    private function tenant_services_settings_menus(MenuWithPermission $menu_instance) : void
    {
        $menu_instance->add_menu_item('services-settings-menu-items', [
            'route' => '#',
            'label' => __('Services'),
            'parent' => null,
            'permissions' => ['service-list','service-create','service-edit','service-delete'],
            'icon' => 'mdi mdi-file',
        ]);
        $menu_instance->add_menu_item('services-settings-all-page-settings', [
            'route' => 'tenant.admin.service',
            'label' => __('All Services'),
            'parent' => 'services-settings-menu-items',
            'permissions' => ['service-list'],
        ]);
        $menu_instance->add_menu_item('services-settings-add-page-settings', [
            'route' => 'tenant.admin.service.add',
            'label' => __('Add Service'),
            'parent' => 'services-settings-menu-items',
            'permissions' => ['service-create'],
        ]);

        $menu_instance->add_menu_item('services-settings-category-page-settings', [
            'route' => 'tenant.admin.service.category',
            'label' => __('Category'),
            'parent' => 'services-settings-menu-items',
            'permissions' => ['service-category-list'],
        ]);

    }

    private function tenant_pages_settings_menus(MenuWithPermission $menu_instance) : void
    {
        $menu_instance->add_menu_item('pages-settings-menu-items', [
            'route' => '#',
            'label' => __('Pages'),
            'parent' => null,
            'permissions' => ['page-list','page-create','page-edit','page-delete'],
            'icon' => 'mdi mdi-file',
        ]);
        $menu_instance->add_menu_item('pages-settings-all-page-settings', [
            'route' => 'tenant.admin.pages',
            'label' => __('All Pages'),
            'parent' => 'pages-settings-menu-items',
            'permissions' => ['page-list'],
        ]);
        $menu_instance->add_menu_item('pages-settings-new-page-settings', [
            'route' => 'tenant.admin.pages.create',
            'label' => __('New Pages'),
            'parent' => 'pages-settings-menu-items',
            'permissions' => ['page-create'],
        ]);
    }

    private function tenant_brands_settings_menus(MenuWithPermission $menu_instance) : void
    {
        $menu_instance->add_menu_item('brands', [
            'route' => 'tenant.admin.brands',
            'label' => __('Brands'),
            'parent' => null,
            'permissions' => [ 'brand-list','brand-create','brand-edit','brand-delete'],
            'icon' => 'mdi mdi-slack',
        ]);
    }

    private function tenant_blog_settings_menus(MenuWithPermission $menu_instance) : void
    {
        $menu_instance->add_menu_item('blog-settings-menu-items', [
            'route' => '#',
            'label' => __('Blogs'),
            'parent' => null,
            'permissions' => ['blog-list','blog-create','blog-edit','blog-delete','blog-settings'],
            'icon' => 'mdi mdi-blogger',
        ]);

        $menu_instance->add_menu_item('blog-all-settings-menu-items', [
            'route' => 'tenant.admin.blog',
            'label' => __('All Blogs'),
            'parent' => 'blog-settings-menu-items',
            'permissions' => ['blog-list'],
        ]);

        $menu_instance->add_menu_item('blog-add-settings-menu-items', [
            'route' => 'tenant.admin.blog.new',
            'label' => __('Add New Blog'),
            'parent' => 'blog-settings-menu-items',
            'permissions' => ['blog-create'],
        ]);

        $menu_instance->add_menu_item('blog-category-settings-all', [
            'route' => 'tenant.admin.blog.category',
            'label' => __('Blog Category'),
            'parent' => 'blog-settings-menu-items',
            'permissions' => ['blog-category-list'],
        ]);

        $menu_instance->add_menu_item('blog-settings-all', [
            'route' => 'tenant.admin.blog.settings',
            'label' => __('Settings'),
            'parent' => 'blog-settings-menu-items',
            'permissions' => ['blog-settings'],
        ]);

    }

    private function tenant_advertisement_settings_menus(MenuWithPermission $menu_instance) : void
    {
        $menu_instance->add_menu_item('advertisement-settings-menu-items', [
            'route' => '#',
            'label' => __('Advertisement'),
            'parent' => null,
            'permissions' => ['advertisement-list','advertisement-create','advertisement-edit','advertisement-delete','advertisement-settings'],
            'icon' => 'mdi mdi-blogger',
        ]);

        $menu_instance->add_menu_item('advertisement-all-settings-menu-items', [
            'route' => 'tenant.admin.advertisement',
            'label' => __('All Advertise'),
            'parent' => 'advertisement-settings-menu-items',
            'permissions' => ['advertisement-list'],
        ]);

        $menu_instance->add_menu_item('advertisement-add-settings-menu-items', [
            'route' => 'tenant.admin.advertisement.new',
            'label' => __('Add Advertise'),
            'parent' => 'advertisement-settings-menu-items',
            'permissions' => ['advertisement-create'],
        ]);

    }

    private function tenant_testimonial_settings_menus(MenuWithPermission $menu_instance) : void
    {
        $menu_instance->add_menu_item('testimonial', [
            'route' => 'tenant.admin.testimonial',
            'label' => __('Testimonial'),
            'parent' => null,
            'permissions' => ['testimonial-list', 'testimonial-create','testimonial-edit','testimonial-delete'],
            'icon' => 'mdi mdi-format-quote-close',
        ]);
    }

    private function tenant_support_ticket_settings_menus(MenuWithPermission $menu_instance) : void
    {
        $menu_instance->add_menu_item('support-tickets-settings-menu-items', [
            'route' => '#',
            'label' => __('Support Tickets'),
            'parent' => null,
            'permissions' => ['support-ticket-list','support-ticket-create','support-ticket-edit','support-ticket-delete',],
            'icon' => 'mdi mdi-folder-outline',
        ]);

        $menu_instance->add_menu_item('support-ticket-settings-all', [
            'route' => 'tenant.admin.support.ticket.all',
            'label' => __('All Tickets'),
            'parent' => 'support-tickets-settings-menu-items',
            'permissions' => ['support-ticket-list'],
        ]);

        $menu_instance->add_menu_item('support-ticket-settings-add', [
            'route' => 'tenant.admin.support.ticket.new',
            'label' => __('Add New Ticket'),
            'parent' => 'support-tickets-settings-menu-items',
            'permissions' => ['support-ticket-create'],
        ]);

        $menu_instance->add_menu_item('support-ticket-settings-department', [
            'route' => 'tenant.admin.support.ticket.department',
            'label' => __('Departments'),
            'parent' => 'support-tickets-settings-menu-items',
            'permissions' => ['support-ticket-department-list','support-ticket-department-create','support-ticket-department-edit','support-ticket-department-delete',],
        ]);

        $menu_instance->add_menu_item('support-ticket-settings-setting', [
            'route' => 'tenant.admin.support.ticket.page.settings',
            'label' => __('Page Settings'),
            'parent' => 'support-tickets-settings-menu-items',
            'permissions' => [],
        ]);
    }

    private function tenant_form_builder_settings_menus(MenuWithPermission $menu_instance) : void
    {
        $menu_instance->add_menu_item('form-builder-settings-menu-items', [
            'route' => '#',
            'label' => __('Form Builder'),
            'parent' => null,
            'permissions' => ['form-builder'],
            'icon' => 'mdi mdi-folder-outline',
        ]);

        $menu_instance->add_menu_item('form-builder-settings-all', [
            'route' => 'tenant.admin.form.builder.all',
            'label' => __('Custom From Builder'),
            'parent' => 'form-builder-settings-menu-items',
            'permissions' => ['form-builder'],
        ]);

        $menu_instance->add_menu_item('form-builder-settings-contact-message', [
            'route' => 'tenant.admin.contact.message.all',
            'label' => __('All Form Submission'),
            'parent' => 'form-builder-settings-menu-items',
            'permissions' => ['form-builder'],
        ]);
    }

    private function tenant_appearance_settings_menus(MenuWithPermission $menu_instance) : void
    {
        $menu_instance->add_menu_item('appearance-settings-menu-items', [
            'route' => '#',
            'label' => __('Appearance Settings'),
            'parent' => null,
            'permissions' => ['menu-manage','topbar-manage','widget-builder','other-settings'],
            'icon' => 'mdi mdi-folder-outline',
        ]);

        $menu_instance->add_menu_item('theme-settings-all-tenant', [
            'route' => 'tenant.admin.theme',
            'label' => __('Theme Manage'),
            'parent' => 'appearance-settings-menu-items',
            'permissions' => ['theme-manage'],
        ]);

        $menu_instance->add_menu_item('menu-settings-all', [
            'route' => 'tenant.admin.menu',
            'label' => __('Menu Manage'),
            'parent' => 'appearance-settings-menu-items',
            'permissions' => ['menu-manage'],
        ]);

        $menu_instance->add_menu_item('widget-builder-settings-all', [
            'route' => 'tenant.admin.widgets',
            'label' => __('Widget Builder'),
            'parent' => 'appearance-settings-menu-items',
            'permissions' => ['widget-builder'],
        ]);

        $menu_instance->add_menu_item('topbar-settings-all', [
            'route' => 'tenant.admin.topbar.settings',
            'label' => __('Topbar Settings'),
            'parent' => 'appearance-settings-menu-items',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('other-settings', [
            'route' => 'tenant.admin.other.settings',
            'label' => __('Other Settings'),
            'parent' => 'appearance-settings-menu-items',
            'permissions' => ['other-settings'],
        ]);

        $menu_instance->add_menu_item('404-settings-all', [
            'route' => 'tenant.admin.404.page.settings',
            'label' => __('404 Settings'),
            'parent' => 'appearance-settings-menu-items',
            'permissions' => [],
        ]);

        $menu_instance->add_menu_item('maintenance-settings-all', [
            'route' => 'tenant.admin.maintains.page.settings',
            'label' => __('Maintenance Settings'),
            'parent' => 'appearance-settings-menu-items',
            'permissions' => [],
        ]);
    }

    private function tenant_general_settings_menus(MenuWithPermission $menu_instance) : void
    {
        $menu_instance->add_menu_item('general-settings-menu-items', [
            'route' => '#',
            'label' => __('General Settings'),
            'parent' => null,
            'permissions' => ['general-settings-page-settings','general-settings-site-identity','general-settings-basic-settings','general-settings-color-settings',
                'general-settings-typography-settings','general-settings-seo-settings','general-settings-payment-settings','general-settings-third-party-script-settings',
                'general-settings-smtp-settings','general-settings-custom-css-settings','general-settings-custom-js-settings','general-settings-database-upgrade-settings',
                'general-settings-cache-clear-settings','general-settings-license-settings'],
            'icon' => 'mdi mdi-settings',
        ]);
        $menu_instance->add_menu_item('general-settings-reading-settings', [
            'route' => 'tenant.admin.general.page.settings',
            'label' => __('Page Settings'),
            'parent' => 'general-settings-menu-items',
            'permissions' => ['general-settings-page-settings'],
        ]);

        $menu_instance->add_menu_item('general-settings-site-identity', [
            'route' => 'tenant.admin.general.site.identity',
            'label' => __('Site Identity'),
            'parent' => 'general-settings-menu-items',
            'permissions' => ['general-settings-site-identity'],
        ]);
        $menu_instance->add_menu_item('general-settings-basic-settings', [
            'route' => 'tenant.admin.general.basic.settings',
            'label' => __('Basic Settings'),
            'parent' => 'general-settings-menu-items',
            'permissions' => ['general-settings-basic-settings'],
        ]);
        $menu_instance->add_menu_item('general-settings-color-settings', [
            'route' => 'tenant.admin.general.color.settings',
            'label' => __('Color Settings'),
            'parent' => 'general-settings-menu-items',
            'permissions' => ['general-settings-color-settings'],
        ]);
        $menu_instance->add_menu_item('general-settings-typography-settings', [
            'route' => 'tenant.admin.general.typography.settings',
            'label' => __('Typography Settings'),
            'parent' => 'general-settings-menu-items',
            'permissions' => ['general-settings-typography-settings'],
        ]);
        $menu_instance->add_menu_item('general-settings-seo-settings', [
            'route' => 'tenant.admin.general.seo.settings',
            'label' => __('SEO Settings'),
            'parent' => 'general-settings-menu-items',
            'permissions' => ['general-settings-seo-settings'],
        ]);
//        $menu_instance->add_menu_item('general-settings-payment-gateway-settings', [
//            'route' => 'tenant.admin.general.payment.settings',
//            'label' => __('Payment Settings'),
//            'parent' => 'general-settings-menu-items',
//            'permissions' => ['general-settings-payment-settings'],
//        ]);
        $menu_instance->add_menu_item('general-settings-third-party-script-settings', [
            'route' => 'tenant.admin.general.third.party.script.settings',
            'label' => __('Third Party Script'),
            'parent' => 'general-settings-menu-items',
            'permissions' => ['general-settings-third-party-script-settings'],
        ]);
        $menu_instance->add_menu_item('general-settings-email-settings', [
            'route' => 'tenant.admin.general.email.settings',
            'label' => __('Email Settings'),
            'parent' => 'general-settings-menu-items',
            'permissions' => ['general-settings-smtp-settings'],
        ]);
        $menu_instance->add_menu_item('general-settings-custom-css-settings', [
            'route' => 'tenant.admin.general.custom.css.settings',
            'label' => __('Custom CSS'),
            'parent' => 'general-settings-menu-items',
            'permissions' => ['general-settings-custom-css-settings'],
        ]);
        $menu_instance->add_menu_item('general-settings-custom-js-settings', [
            'route' => 'tenant.admin.general.custom.js.settings',
            'label' => __('Custom JS'),
            'parent' => 'general-settings-menu-items',
            'permissions' => ['general-settings-custom-js-settings'],
        ]);

        $menu_instance->add_menu_item('general-settings-cache-settings', [
            'route' => 'tenant.admin.general.cache.settings',
            'label' => __('Cache Settings'),
            'parent' => 'general-settings-menu-items',
            'permissions' => [ 'general-settings-cache-clear-settings'],
        ]);
        $menu_instance->add_menu_item('general-settings-gdpr-settings', [
            'route' => 'tenant.admin.general.gdpr.settings',
            'label' => __('GDPR Settings'),
            'parent' => 'general-settings-menu-items',
            'permissions' => [],
        ]);
        $menu_instance->add_menu_item('general-settings-sitemap-settings', [
            'route' => 'tenant.admin.general.sitemap.settings',
            'label' => __('Sitemap Settings'),
            'parent' => 'general-settings-menu-items',
            'permissions' => [],
        ]);
    }

    private function tenant_payment_gateway_manage_menus(MenuWithPermission $menu_instance) : void
    {

        $current_tenant_payment_data = tenant()->payment_log()?->first() ?? null;
        $package = $current_tenant_payment_data->package ?? [];
        $all_features = $package->plan_features ?? [];
        $check_all_feature = $all_features->pluck('feature_name')->toArray();

        $menu_instance->add_menu_item('payment_gateway-manage-settings-menu-items', [
            'route' => '#',
            'label' => __('Payment Settings'),
            'parent' => null,
            'permissions' => ['user-website-issue-list'],
            'icon' => 'mdi mdi-settings',
        ]);

        $menu_instance->add_menu_item('currency-settings-list-menu-items', [
            'route' => 'tenant.admin.payment.currency.settings',
            'label' => __('Currencies'),
            'parent' => 'payment_gateway-manage-settings-menu-items',
            'permissions' => [],
        ]);

        if (in_array('paypal',$check_all_feature)) {
            $menu_instance->add_menu_item('paypal-settings-list-menu-items', [
                'route' => 'tenant.admin.payment.paypal.settings',
                'label' => __('Paypal'),
                'parent' => 'payment_gateway-manage-settings-menu-items',
                'permissions' => [],
            ]);
        }

        if (in_array('paytm',$check_all_feature)) {
            $menu_instance->add_menu_item('paytm-settings-list-menu-items', [
                'route' => 'tenant.admin.payment.paytm.settings',
                'label' => __('Paytm'),
                'parent' => 'payment_gateway-manage-settings-menu-items',
                'permissions' => [],
            ]);
        }

        if (in_array('stripe',$check_all_feature)) {
            $menu_instance->add_menu_item('stripe-settings-list-menu-items', [
                'route' => 'tenant.admin.payment.stripe.settings',
                'label' => __('Stripe'),
                'parent' => 'payment_gateway-manage-settings-menu-items',
                'permissions' => [],
            ]);
        }

        if (in_array('razorpay',$check_all_feature)) {
            $menu_instance->add_menu_item('razorpay-settings-list-menu-items', [
                'route' => 'tenant.admin.payment.razorpay.settings',
                'label' => __('Razorpay'),
                'parent' => 'payment_gateway-manage-settings-menu-items',
                'permissions' => [],
            ]);
        }


        if (in_array('paystack',$check_all_feature)) {
            $menu_instance->add_menu_item('paystack-settings-list-menu-items', [
                'route' => 'tenant.admin.payment.paystack.settings',
                'label' => __('Paystack'),
                'parent' => 'payment_gateway-manage-settings-menu-items',
                'permissions' => [],
            ]);
        }


        if (in_array('mollie',$check_all_feature)) {
            $menu_instance->add_menu_item('mollie-settings-list-menu-items', [
                'route' => 'tenant.admin.payment.mollie.settings',
                'label' => __('Mollie'),
                'parent' => 'payment_gateway-manage-settings-menu-items',
                'permissions' => [],
            ]);
        }

        if (in_array('payfast',$check_all_feature)) {
            $menu_instance->add_menu_item('payfast-settings-list-menu-items', [
                'route' => 'tenant.admin.payment.payfast.settings',
                'label' => __('Payfast'),
                'parent' => 'payment_gateway-manage-settings-menu-items',
                'permissions' => [],
            ]);
        }

        if (in_array('midtrans',$check_all_feature)) {
            $menu_instance->add_menu_item('midtrans-settings-list-menu-items', [
                'route' => 'tenant.admin.payment.midtrans.settings',
                'label' => __('Midtrans'),
                'parent' => 'payment_gateway-manage-settings-menu-items',
                'permissions' => [],
            ]);
        }

        if (in_array('cashfree',$check_all_feature)) {
            $menu_instance->add_menu_item('cashfree-settings-list-menu-items', [
                'route' => 'tenant.admin.payment.cashfree.settings',
                'label' => __('Cashfree'),
                'parent' => 'payment_gateway-manage-settings-menu-items',
                'permissions' => [],
            ]);
        }

        if (in_array('instamojo',$check_all_feature)) {
            $menu_instance->add_menu_item('instamojo-settings-list-menu-items', [
                'route' => 'tenant.admin.payment.instamojo.settings',
                'label' => __('Instamojo'),
                'parent' => 'payment_gateway-manage-settings-menu-items',
                'permissions' => [],
            ]);
        }

        if (in_array('marcadopago',$check_all_feature)) {
            $menu_instance->add_menu_item('marcadopago-settings-list-menu-items', [
                'route' => 'tenant.admin.payment.marcadopago.settings',
                'label' => __('Marcadopago'),
                'parent' => 'payment_gateway-manage-settings-menu-items',
                'permissions' => [],
            ]);
        }

        if (in_array('zitopay',$check_all_feature)) {
            $menu_instance->add_menu_item('zitopay-settings-list-menu-items', [
                'route' => 'tenant.admin.payment.zitopay.settings',
                'label' => __('Zitopay'),
                'parent' => 'payment_gateway-manage-settings-menu-items',
                'permissions' => [],
            ]);
        }

        if (in_array('squareup',$check_all_feature)) {
            $menu_instance->add_menu_item('squareup-settings-list-menu-items', [
                'route' => 'tenant.admin.payment.squareup.settings',
                'label' => __('Squareup'),
                'parent' => 'payment_gateway-manage-settings-menu-items',
                'permissions' => [],
            ]);
        }

        if (in_array('cinetpay',$check_all_feature)) {
            $menu_instance->add_menu_item('cinetpay-settings-list-menu-items', [
                'route' => 'tenant.admin.payment.cinetpay.settings',
                'label' => __('Cinetpay'),
                'parent' => 'payment_gateway-manage-settings-menu-items',
                'permissions' => [],
            ]);
        }

        if (in_array('paytabs',$check_all_feature)) {
            $menu_instance->add_menu_item('paytabs-settings-list-menu-items', [
                'route' => 'tenant.admin.payment.paytabs.settings',
                'label' => __('Paytabs'),
                'parent' => 'payment_gateway-manage-settings-menu-items',
                'permissions' => [],
            ]);
        }

        if (in_array('billplz',$check_all_feature)) {
            $menu_instance->add_menu_item('billplz-settings-list-menu-items', [
                'route' => 'tenant.admin.payment.billplz.settings',
                'label' => __('Billplz'),
                'parent' => 'payment_gateway-manage-settings-menu-items',
                'permissions' => [],
            ]);
        }

        if (in_array('manual_payment_',$check_all_feature)) {
            $menu_instance->add_menu_item('manual_payment-settings-list-menu-items', [
                'route' => 'tenant.admin.payment.manual_payment.settings',
                'label' => __('Manual Payment'),
                'parent' => 'payment_gateway-manage-settings-menu-items',
                'permissions' => [],
            ]);
        }

        if (in_array('flutterwave',$check_all_feature)) {
            $menu_instance->add_menu_item('flutterwave-settings-list-menu-items', [
                'route' => 'tenant.admin.payment.flutterwave.settings',
                'label' => __('Flutterwave'),
                'parent' => 'payment_gateway-manage-settings-menu-items',
                'permissions' => [],
            ]);
        }

        if (in_array('toyyibpay',$check_all_feature)) {
            $menu_instance->add_menu_item('toyyibpay-settings-list-menu-items', [
                'route' => 'tenant.admin.payment.toyyibpay.settings',
                'label' => __('Toyyibpay'),
                'parent' => 'payment_gateway-manage-settings-menu-items',
                'permissions' => [],
            ]);
        }

        if (in_array('pagali',$check_all_feature)) {
            $menu_instance->add_menu_item('pagali-settings-list-menu-items', [
                'route' => 'tenant.admin.payment.pagali.settings',
                'label' => __('Pagali'),
                'parent' => 'payment_gateway-manage-settings-menu-items',
                'permissions' => [],
            ]);
        }

        if (in_array('authorizenet',$check_all_feature)) {
            $menu_instance->add_menu_item('authorizenet-settings-list-menu-items', [
                'route' => 'tenant.admin.payment.authorizenet.settings',
                'label' => __('Authorizenet'),
                'parent' => 'payment_gateway-manage-settings-menu-items',
                'permissions' => [],
            ]);
        }

        if (in_array('sitesway',$check_all_feature)) {
            $menu_instance->add_menu_item('sitesway-settings-list-menu-items', [
                'route' => 'tenant.admin.payment.sitesway.settings',
                'label' => __('Sitesway'),
                'parent' => 'payment_gateway-manage-settings-menu-items',
                'permissions' => [],
            ]);
        }

        if (in_array('manual_payment_',$check_all_feature)) {
            $menu_instance->add_menu_item('manual_payment-settings-list-menu-items', [
                'route' => 'tenant.admin.payment.manual_payment.settings',
                'label' => __('Manual Payment'),
                'parent' => 'payment_gateway-manage-settings-menu-items',
                'permissions' => [],
            ]);
        }

        if (in_array('bank_transfer',$check_all_feature)) {
            $menu_instance->add_menu_item('bank-transfer-settings-list-menu-items', [
                'route' => 'tenant.admin.payment.bank_transfer.settings',
                'label' => __('Bank Transfer'),
                'parent' => 'payment_gateway-manage-settings-menu-items',
                'permissions' => [],
            ]);
        }

        if (in_array('kinetic',$check_all_feature)) {
            $menu_instance->add_menu_item('kinetic-settings-list-menu-items', [
                'route' => 'tenant.admin.payment.kinetic.settings',
                'label' => __('kinetic'),
                'parent' => 'payment_gateway-manage-settings-menu-items',
                'permissions' => [],
            ]);
        }

    }

    private function tenant_users_manage_menus(MenuWithPermission $menu_instance) : void
    {
        $menu_instance->add_menu_item('users-manage-settings-menu-items', [
            'route' => '#',
            'label' => __('Users Manage'),
            'parent' => null,
            'permissions' => [],
            'icon' => 'mdi mdi-account-multiple',
        ]);
        $menu_instance->add_menu_item('users-manage-settings-list-menu-items', [
            'route' => 'tenant.admin.user',
            'label' => __('All Users'),
            'parent' => 'users-manage-settings-menu-items',
            'permissions' => [],
        ]);
        $menu_instance->add_menu_item('users-manage-settings-add-new-menu-items', [
            'route' => 'tenant.admin.user.new',
            'label' => __('Add New'),
            'parent' => 'users-manage-settings-menu-items',
            'permissions' => [],
        ]);
    }

    private function tenant_payment_manage_menus(MenuWithPermission $menu_instance) : void
    {
        $menu_instance->add_menu_item('tenant-payment-manage-settings-menu-items', [
            'route' => '#',
            'label' => __('My Package Orders'),
            'parent' => null,
            'permissions' => [],
            'icon' => 'mdi mdi-account-multiple',
        ]);
        $menu_instance->add_menu_item('my-payment-manage-my-logs-settings-menu-items', [
            'route' => 'tenant.my.package.order.payment.logs',
            'label' => __('My Payment Logs'),
            'parent' => 'tenant-payment-manage-settings-menu-items',
            'permissions' => [],
        ]);
    }

    private function tenant_custom_domain_request_settings_menus(MenuWithPermission $menu_instance) : void
    {
        $menu_instance->add_menu_item('custom-domain-request', [
            'route' => 'tenant.admin.custom.domain.requests',
            'label' => __('Custom Domain'),
            'parent' => null,
            'permissions' => ['custom-domain'],
            'icon' => 'mdi mdi-format-quote-close',
        ]);
    }

    private function tenant_admin_manage_menus(MenuWithPermission $menu_instance) : void
    {
        $menu_instance->add_menu_item('admin-manage-settings-menu-items', [
            'route' => '#',
            'label' => __('Staff Role Manage'),
            'parent' => null,
            'permissions' => [],
            'icon' => 'mdi mdi-account-multiple',
        ]);
        $menu_instance->add_menu_item('admins-manage-settings-list-menu-items', [
            'route' => 'tenant.admin.all.user',
            'label' => __('All Staff'),
            'parent' => 'admin-manage-settings-menu-items',
            'permissions' => [],
        ]);
        $menu_instance->add_menu_item('admins-manage-settings-add-new-menu-items', [
            'route' => 'tenant.admin.new.user',
            'label' => __('Add New Staff'),
            'parent' => 'admin-manage-settings-menu-items',
            'permissions' => [],
        ]);
        $menu_instance->add_menu_item('admins-role-manage-settings-add-new-menu-items', [
            'route' => 'tenant.admin.all.admin.role',
            'label' => __('All Staff Role'),
            'parent' => 'admin-manage-settings-menu-items',
            'permissions' => [],
        ]);
    }


    //Ecommerce


    private function tenant_order_manage_settings_menus(MenuWithPermission $menu_instance): void
    {
        $menu_instance->add_menu_item('product-order-manage-settings', [
            'route' => '#',
            'label' => __('Product Order Manage'),
            'parent' => null,
            'permissions' => ['product-order-all-order', 'product-order-pending-order',
                'product-progress-order', 'product-order-complete', 'product-order-success-page', 'product-order-cancel-page',
                'product-order-page-manage', 'product-order-report', 'product-order-payment-logs', 'product-order-payment-report',
                'product-order-manage-settings'
            ],
            'icon' => 'mdi mdi-cart',
        ]);
        $menu_instance->add_menu_item('product-order-manage-settings-all-order', [
            'route' => 'tenant.admin.product.order.manage.all',
            'label' => __('All Order'),
            'parent' => 'product-order-manage-settings',
            'permissions' => ['product-order-all-order'],
        ]);
        $menu_instance->add_menu_item('product-order-manage-settings-success-page', [
            'route' => 'tenant.admin.product.order.success.page',
            'label' => __('Success Order Page'),
            'parent' => 'product-order-manage-settings',
            'permissions' => ['product-order-success-page'],
        ]);
        $menu_instance->add_menu_item('product-order-manage-settings-cancel-page', [
            'route' => 'tenant.admin.product.order.cancel.page',
            'label' => __('Cancel Order Page'),
            'parent' => 'product-order-manage-settings',
            'permissions' => ['product-order-cancel-page'],
        ]);
        $menu_instance->add_menu_item('product-order-manage-settings-order-settings', [
            'route' => 'tenant.admin.product.order.settings',
            'label' => __('Order Settings'),
            'parent' => 'product-order-manage-settings',
            'permissions' => ['product-order-manage-settings'],
        ]);
    }

    private function tenant_badge_settings_menus(MenuWithPermission $menu_instance): void
    {
        $menu_instance->add_menu_item('badge-settings-menu-items', [
            'route' => '#',
            'label' => __('Badge Manage'),
            'parent' => null,
            'permissions' => [
                'badge-list', 'badge-create', 'badge-edit', 'badge-delete',
            ],
            'icon' => 'mdi mdi-label',
        ]);

        $menu_instance->add_menu_item('badge-all-settings-menu-items', [
            'route' => 'tenant.admin.badge.all',
            'label' => __('Badge Manage'),
            'parent' => 'badge-settings-menu-items',
            'permissions' => ['badge-list'],
        ]);

        $menu_instance->add_menu_item('state-all-settings-menu-items', [
            'route' => 'tenant.admin.state.all',
            'label' => __('State Manage'),
            'parent' => 'country-settings-menu-items',
            'permissions' => ['state-list'],
        ]);
    }

    private function tenant_country_settings_menus(MenuWithPermission $menu_instance): void
    {
        $menu_instance->add_menu_item('country-settings-menu-items', [
            'route' => '#',
            'label' => __('Country Manage'),
            'parent' => null,
            'permissions' => [
                'country-list', 'country-create', 'country-edit', 'country-delete',
                'state-list', 'state-create', 'state-edit', 'state-delete'
            ],
            'icon' => 'mdi mdi-map',
        ]);

        $menu_instance->add_menu_item('country-all-settings-menu-items', [
            'route' => 'tenant.admin.country.all',
            'label' => __('Country Manage'),
            'parent' => 'country-settings-menu-items',
            'permissions' => ['country-list'],
        ]);

        $menu_instance->add_menu_item('state-all-settings-menu-items', [
            'route' => 'tenant.admin.state.all',
            'label' => __('State Manage'),
            'parent' => 'country-settings-menu-items',
            'permissions' => ['state-list'],
        ]);
    }

    private function tenant_tax_settings_menus(MenuWithPermission $menu_instance): void
    {
        $menu_instance->add_menu_item('tax-settings-menu-items', [
            'route' => '#',
            'label' => __('Tax Manage'),
            'parent' => null,
            'permissions' => [
                'country-tax-list', 'country-tax-create', 'country-tax-edit', 'country-tax-delete',
                'state-tax-list', 'state-tax-create', 'state-tax-edit', 'state-tax-delete'
            ],
            'icon' => 'mdi mdi-cash',
        ]);

        $menu_instance->add_menu_item('tax-country-settings-menu-items', [
            'route' => 'tenant.admin.tax.country.all',
            'label' => __('Country Tax Manage'),
            'parent' => 'tax-settings-menu-items',
            'permissions' => ['country-tax-list'],
        ]);

        $menu_instance->add_menu_item('tax-state-settings-menu-items', [
            'route' => 'tenant.admin.tax.state.all',
            'label' => __('State Tax Manage'),
            'parent' => 'tax-settings-menu-items',
            'permissions' => ['state-tax-list'],
        ]);
    }

    private function tenant_shipping_settings_menus(MenuWithPermission $menu_instance): void
    {
        $menu_instance->add_menu_item('shipping-settings-menu-items', [
            'route' => '#',
            'label' => __('Shipping Manage'),
            'parent' => null,
            'permissions' => [
                'shipping-method-list', 'shipping-method-create', 'shipping-method-edit', 'shipping-method-delete', 'shipping-method-make',
                'shipping-zone-list', 'shipping-zone-create', 'shipping-zone-edit', 'shipping-zone-delete'
            ],
            'icon' => 'mdi mdi-truck',
        ]);

        $menu_instance->add_menu_item('shipping-zone-settings-menu-items', [
            'route' => 'tenant.admin.shipping.zone.all',
            'label' => __('Shipping Zone'),
            'parent' => 'shipping-settings-menu-items',
            'permissions' => ['shipping-zone-list'],
        ]);

        $menu_instance->add_menu_item('shipping-method-settings-menu-items', [
            'route' => 'tenant.admin.shipping.method.all',
            'label' => __('Shipping Method'),
            'parent' => 'shipping-settings-menu-items',
            'permissions' => ['shipping-method-list'],
        ]);
    }

    private function tenant_coupon_settings_menus(MenuWithPermission $menu_instance): void
    {
        $menu_instance->add_menu_item('coupon-settings-menu-items', [
            'route' => '#',
            'label' => __('Coupon Manage'),
            'parent' => null,
            'permissions' => [
                'product-coupon-list', 'product-coupon-create', 'product-coupon-edit', 'product-coupon-delete',
            ],
            'icon' => 'mdi mdi-ticket-percent',
        ]);

        $menu_instance->add_menu_item('product-coupon-settings-menu-items', [
            'route' => 'tenant.admin.product.coupon.all',
            'label' => __('All Coupon'),
            'parent' => 'coupon-settings-menu-items',
            'permissions' => ['product-coupon-list'],
        ]);
    }

    private function tenant_attribute_settings_menus(MenuWithPermission $menu_instance): void
    {
        $menu_instance->add_menu_item('product-attribute-menu-items', [
            'route' => '#',
            'label' => __('Attribute'),
            'parent' => null,
            'permissions' => [
                'product-category-list', 'product-category-create', 'product-category-edit', 'product-category-delete',
                'product-sub-category-list', 'product-sub-category-create', 'product-sub-category-edit', 'product-sub-category-delete',
                'product-child-category-list', 'product-child-category-create', 'product-child-category-edit', 'product-child-category-delete',
                'product-tag-list', 'product-tag-create', 'product-tag-edit', 'product-tag-delete',
                'product-unit-list', 'product-unit-create', 'product-unit-edit', 'product-unit-delete',
                'product-color-list', 'product-color-create', 'product-color-edit', 'product-color-delete',
                'product-size-list', 'product-size-create', 'product-size-edit', 'product-size-delete',
                'product-delivery-option-list', 'product-delivery-option-create', 'product-delivery-option-edit', 'product-delivery-option-delete'
            ],
            'icon' => 'mdi mdi-format-list-checks',
        ]);

        $menu_instance->add_menu_item('product-category-settings-menu-items', [
            'route' => 'tenant.admin.product.category.all',
            'label' => __('Category Manage'),
            'parent' => 'product-attribute-menu-items',
            'permissions' => ['product-category-list'],
        ]);

        $menu_instance->add_menu_item('product-sub-category-settings-menu-items', [
            'route' => 'tenant.admin.product.subcategory.all',
            'label' => __('Subcategory Manage'),
            'parent' => 'product-attribute-menu-items',
            'permissions' => ['product-sub-category-list'],
        ]);

        $menu_instance->add_menu_item('product-child-category-settings-menu-items', [
            'route' => 'tenant.admin.product.child-category.all',
            'label' => __('Child Category Manage'),
            'parent' => 'product-attribute-menu-items',
            'permissions' => ['product-child-category-list'],
        ]);

        $menu_instance->add_menu_item('product-tag-settings-menu-items', [
            'route' => 'tenant.admin.product.tag.all',
            'label' => __('Tags Manage'),
            'parent' => 'product-attribute-menu-items',
            'permissions' => ['product-tag-list'],
        ]);

        $menu_instance->add_menu_item('product-unit-settings-menu-items', [
            'route' => 'tenant.admin.product.units.all',
            'label' => __('Unit Manage'),
            'parent' => 'product-attribute-menu-items',
            'permissions' => ['product-unit-list'],
        ]);
        $menu_instance->add_menu_item('product-color-settings-menu-items', [
            'route' => 'tenant.admin.product.colors.all',
            'label' => __('Color Manage'),
            'parent' => 'product-attribute-menu-items',
            'permissions' => ['product-color-list'],
        ]);
        $menu_instance->add_menu_item('product-size-settings-menu-items', [
            'route' => 'tenant.admin.product.sizes.all',
            'label' => __('Size Manage'),
            'parent' => 'product-attribute-menu-items',
            'permissions' => ['product-size-list'],
        ]);
        $menu_instance->add_menu_item('product-brand-settings-menu-items', [
            'route' => 'tenant.admin.product.brand.manage.all',
            'label' => __('Product Brand Manage'),
            'parent' => 'product-attribute-menu-items',
            'permissions' => ['product-brand-list'],
        ]);
        $menu_instance->add_menu_item('delivery-option-settings-menu-items', [
            'route' => 'tenant.admin.product.delivery.option.all',
            'label' => __('Delivery Option Manage'),
            'parent' => 'product-attribute-menu-items',
            'permissions' => ['product-delivery-option-list'],
        ]);
        $menu_instance->add_menu_item('product-delivery-option-settings-menu-items', [
            'route' => 'tenant.admin.products.attributes.all',
            'label' => __('Product Attribute'),
            'parent' => 'product-attribute-menu-items',
            'permissions' => ['product-attribute-list'],
        ]);
    }

    private function tenant_product_settings_menus(MenuWithPermission $menu_instance): void
    {
        $menu_instance->add_menu_item('product-settings-menu-items', [
            'route' => '#',
            'label' => __('Products'),
            'parent' => null,
            'permissions' => ['product-list', 'product-create', 'product-edit', 'product-delete', 'product-settings'],
            'icon' => 'mdi mdi-shopping',
        ]);

        $menu_instance->add_menu_item('product-all-settings-menu-items', [
            'route' => 'tenant.admin.product.all',
            'label' => __('All Products'),
            'parent' => 'product-settings-menu-items',
            'permissions' => ['product-list'],
        ]);

        $menu_instance->add_menu_item('product-create-menu-items', [
            'route' => 'tenant.admin.product.create',
            'label' => __('Add New Product'),
            'parent' => 'product-settings-menu-items',
            'permissions' => ['product-create'],
        ]);
    }

    private function tenant_campaign_settings_menus(MenuWithPermission $menu_instance): void
    {
        $menu_instance->add_menu_item('campaign-settings-menu-items', [
            'route' => 'tenant.admin.campaign.all',
            'label' => __('Campaign'),
            'parent' => null,
            'permissions' => ['campaign-list', 'campaign-create', 'campaign-edit', 'campaign-delete', 'campaign-settings'],
            'icon' => 'mdi mdi-shopping',
        ]);
    }

    private function tenant_inventory_settings_menus(MenuWithPermission $menu_instance): void
    {
        $menu_instance->add_menu_item('inventory-settings-menu-items', [
            'route' => '#',
            'label' => __('Inventory'),
            'parent' => null,
            'permissions' => ['inventory-list', 'inventory-create', 'inventory-edit', 'inventory-delete', 'inventory-settings'],
            'icon' => 'mdi mdi-shopping',
        ]);

        $menu_instance->add_menu_item('inventory-manage-settings-menu-items', [
            'route' => 'tenant.admin.product.inventory.all',
            'label' => __('Inventory Manage'),
            'parent' => 'inventory-settings-menu-items',
            'permissions' => ['inventory-list', 'inventory-create', 'inventory-edit', 'inventory-delete', 'inventory-settings'],
        ]);
        $menu_instance->add_menu_item('inventory-stock-settings-menu-items', [
            'route' => 'tenant.admin.product.inventory.settings',
            'label' => __('Inventory Settings'),
            'parent' => 'inventory-settings-menu-items',
            'permissions' => ['inventory-settings'],
        ]);
    }


}
