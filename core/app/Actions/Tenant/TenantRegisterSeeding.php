<?php

namespace App\Actions\Tenant;

use App\Helpers\ImageDataSeedingHelper;
use App\Helpers\SanitizeInput;
use App\Models\Admin;
use App\Models\Language;
use App\Models\Menu;
use App\Models\Page;
use App\Models\TopbarInfo;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\This;
use Spatie\Permission\Models\Role;

class TenantRegisterSeeding
{
    public static function role_permission()
    {
        $permissions = [
            'page-list',
            'page-create',
            'page-edit',
            'page-delete',
            'price-plan-list',
            'price-plan-create',
            'price-plan-edit',
            'price-plan-delete',
            'package-order-all-order',
            'package-order-pending-order',
            'package-order-progress-order',
            'package-order-complete-order',
            'package-order-success-order-page',
            'package-order-cancel-order-page',
            'package-order-order-page-manage',
            'package-order-order-report',
            'package-order-payment-logs',
            'package-order-payment-report',
            'package-order-delete',
            'package-order-edit',
            'testimonial-list',
            'testimonial-create',
            'testimonial-edit',
            'testimonial-delete',
            'brand-list',
            'brand-create',
            'brand-edit',
            'brand-delete',
            'blog-category-list',
            'blog-category-create',
            'blog-category-edit',
            'blog-category-delete',
            'blog-list',
            'blog-create',
            'blog-edit',
            'blog-delete',
            'blog-settings',
            'blog-comments',
            'service-category-list',
            'service-category-create',
            'service-category-edit',
            'service-category-delete',
            'service-list',
            'service-create',
            'service-edit',
            'service-delete',
            'service-settings',
            'service-comments',
            'form-builder',
            'widget-builder',
            'general-settings-page-settings',
            'general-settings-global-navbar-settings',
            'general-settings-global-footer-settings',
            'general-settings-site-identity',
            'general-settings-basic-settings',
            'general-settings-color-settings',
            'general-settings-typography-settings',
            'general-settings-seo-settings',
            'general-settings-payment-settings',
            'general-settings-third-party-script-settings',
            'general-settings-smtp-settings',
            'general-settings-custom-css-settings',
            'general-settings-custom-js-settings',
            'general-settings-database-upgrade-settings',
            'general-settings-cache-clear-settings',
            'general-settings-license-settings',
            'language-list',
            'language-create',
            'language-edit',
            'language-delete',
            'menu-manage',
            'topbar-manage',
            'other-settings',
            'newsletter-list',
            'newsletter-create',
            'newsletter-edit',
            'newsletter-delete',
            'support-ticket-list',
            'support-ticket-create',
            'support-ticket-edit',
            'support-ticket-delete',
            'support-ticket-department-list',
            'support-ticket-department-create',
            'support-ticket-department-edit',
            'support-ticket-department-delete',
        ];

        foreach ($permissions as $permission) {
            \Spatie\Permission\Models\Permission::where(['name' => $permission])->delete();
            \Spatie\Permission\Models\Permission::create(['name' => $permission, 'guard_name' => 'admin']);
        }

        $demo_permissions = [];
        $role = Role::create(['name' => 'Super Admin', 'guard_name' => 'admin']);
        $role->syncPermissions($demo_permissions);

    }

    public static function admin($object)
    {
        $admin = Admin::create([
            'name' => $object->name,
            'username' => $object->username,
            'email' => $object->email,
            'password' => $object->password,
        ]);

        $admin->assignRole('Super Admin');
    }

    public static function language()
    {
        Language::create([
            'name' => __('English (USA)'),
            'direction' => 0,
            'slug' => 'en_US',
            'status' => 1,
            'default' => 1
        ]);

        Language::create([
            'name' => __('Arabic'),
            'direction' => 1,
            'slug' => 'ar',
            'status' => 1,
            'default' => 0
        ]);
    }

    public static function menu()
    {
        $menu = new TenantRegisterSeeding();
        Menu::create([
            'content' => json_encode($menu->menu_content()),
            'title' => 'Primary Menu',
            'status' => 'default',
        ]);
    }

    private function menu_content()
    {
        $data = array(
            0 =>
                array(
                    'ptype' => 'pages',
                    'id' => 1,
                    'antarget' => '',
                    'icon' => '',
                    'menulabel' => '',
                    'pid' => 1,
                ),
            1 =>
                array(
                    'ptype' => 'pages',
                    'id' => 2,
                    'antarget' => '',
                    'icon' => '',
                    'menulabel' => '',
                    'pid' => 2,
                ),
            2 =>
                array(
                    'ptype' => 'pages',
                    'id' => 3,
                    'antarget' => '',
                    'icon' => '',
                    'menulabel' => '',
                    'pid' => 3,
                ),
            3 =>
                array(
                    'ptype' => 'pages',
                    'id' => 4,
                    'antarget' => '',
                    'icon' => '',
                    'menulabel' => '',
                    'pid' => 4,
                ),
            4 =>
                array(
                    'ptype' => 'pages',
                    'id' => 5,
                    'antarget' => '',
                    'icon' => '',
                    'menulabel' => '',
                    'pid' => 5,
                ),
        );

        return $data;
    }

    public static function general_data()
    {
        $icons = ['lab la-twitter','lab la-pinterest-p','las la-user','lab la-facebook-f'];

        for ($i = 0; $i < count($icons); $i++){
            TopbarInfo::create([
                'icon' => $icons[$i],
                'url' => '#',
            ]);
        }

        $default_lang = 'en_US';
        $default_lang_ar = 'ar';

        $site_logo_id = ImageDataSeedingHelper::insert('assets/tenant/frontend/img/logo.png');
        $site_white_logo_id = ImageDataSeedingHelper::insert('assets/tenant/frontend/img/logo-02.png');
        $site_favicon_id = ImageDataSeedingHelper::insert('assets/tenant/frontend/img/logo.png');

        $data = [
            'site_'.$default_lang.'_title'=> 'Demo Site Title',
            'site_'.$default_lang_ar.'_title'=> 'عنوان الموقع التجريبي',
            'site_'.$default_lang.'_tag_line' =>'Demo Site Tag Line',
            'site_'.$default_lang_ar.'_tag_line' =>'سطر علامة الموقع التجريبي',
            'home_one_header_button_'.$default_lang.'_text'=> 'Join With Us',
            'home_one_header_button_'.$default_lang_ar.'_text'=> 'انضم إلينا',
            'language_selector_status'=> 'on',
            'home_page' => 1,
            'global_navbar_variant' => '01',
            'global_footer_variant' => '01',
            'order_form' => '02',
            'site_logo' => $site_logo_id,
            'site_white_logo' => $site_white_logo_id,
            'site_favicon' => $site_favicon_id,
        ];

        foreach ($data as $key => $value){
            update_static_option($key,$value);
        }
    }

    public static function page()
    {
        $default_lang = 'en_US';
        $default_lang_ar = 'ar';

        $page_data = new Page();
        $page_data->slug = Str::slug('home','-',$default_lang);
        $page_data->setTranslation('title',$default_lang, SanitizeInput::esc_html('Home'));
        $page_data->setTranslation('title',$default_lang_ar, SanitizeInput::esc_html('مسكن'));
        $page_data->setTranslation('page_content',$default_lang, 'Home Page One');
        $page_data->visibility = 0;
        $page_data->status = 1;
        $page_data->navbar_variant = '01';
        $page_data->footer_variant = '01';
        $page_data->page_builder = 1;
        $page_data->breadcrumb = 0;

        $Metas = [
            'title' => [$default_lang => SanitizeInput::esc_html('')],
            'description' => [$default_lang => SanitizeInput::esc_html('Demo meta desc')],
            'image' => null,
            //twitter
            'tw_image' => null,
            'tw_title' => SanitizeInput::esc_html('tw title'),
            'tw_description' => SanitizeInput::esc_html('tw desc'),
            //facebook
            'fb_image' => null,
            'fb_title' =>  SanitizeInput::esc_html('fb title'),
            'fb_description' =>  SanitizeInput::esc_html('fb desc'),
        ];

        $page_data->save();
        $page_data->metainfo()->create($Metas);


        $page_data_2 = new Page();
        $page_data_2->slug = Str::slug('about','-',$default_lang);
        $page_data_2->setTranslation('title',$default_lang, SanitizeInput::esc_html('About Us'));
        $page_data_2->setTranslation('title',$default_lang_ar, SanitizeInput::esc_html('معلومات عنا'));
        $page_data_2->setTranslation('page_content',$default_lang, 'About us content');
        $page_data_2->visibility = 0;
        $page_data_2->status = 1;
        $page_data_2->navbar_variant = '01';
        $page_data_2->footer_variant = '01';
        $page_data_2->page_builder = 1;
        $page_data_2->breadcrumb = 1;

        $Metas_2 = [
            'title' => [$default_lang => SanitizeInput::esc_html('Demo Meta Title')],
            'description' => [$default_lang => SanitizeInput::esc_html('Demo meta desc')],
            'image' => null,
            //twitter
            'tw_image' => null,
            'tw_title' => SanitizeInput::esc_html('tw title'),
            'tw_description' => SanitizeInput::esc_html('tw desc'),
            //facebook
            'fb_image' => null,
            'fb_title' =>  SanitizeInput::esc_html('fb title'),
            'fb_description' =>  SanitizeInput::esc_html('fb desc'),
        ];

        $page_data_2->save();
        $page_data_2->metainfo()->create($Metas_2);


        $page_data_3 = new Page();
        $page_data_3->slug = Str::slug('service','-',$default_lang);
        $page_data_3->setTranslation('title',$default_lang, SanitizeInput::esc_html('Service'));
        $page_data_3->setTranslation('title',$default_lang_ar, SanitizeInput::esc_html('خدمة'));
        $page_data_3->setTranslation('page_content',$default_lang, 'Service content');
        $page_data_3->visibility = 0;
        $page_data_3->status = 1;
        $page_data_3->navbar_variant = '01';
        $page_data_3->footer_variant = '01';
        $page_data_3->page_builder = 1;
        $page_data_3->breadcrumb = 0;

        $Metas_3 = [
            'title' => [$default_lang => SanitizeInput::esc_html('Demo Meta Title')],
            'description' => [$default_lang => SanitizeInput::esc_html('Demo meta desc')],
            'image' => null,
            //twitter
            'tw_image' => null,
            'tw_title' => SanitizeInput::esc_html('tw title'),
            'tw_description' => SanitizeInput::esc_html('tw desc'),
            //facebook
            'fb_image' => null,
            'fb_title' =>  SanitizeInput::esc_html('fb title'),
            'fb_description' =>  SanitizeInput::esc_html('fb desc'),
        ];

        $page_data_3->save();
        $page_data_3->metainfo()->create($Metas_3);



        $page_data_4 = new Page();
        $page_data_4->slug = Str::slug('price','-',$default_lang);
        $page_data_4->setTranslation('title',$default_lang, SanitizeInput::esc_html('Price Plan'));
        $page_data_4->setTranslation('title',$default_lang_ar, SanitizeInput::esc_html('خطة الأسعار'));
        $page_data_4->setTranslation('page_content',$default_lang, 'Price plan content');
        $page_data_4->visibility = 0;
        $page_data_4->status = 1;
        $page_data_4->navbar_variant = '01';
        $page_data_4->footer_variant = '01';
        $page_data_4->page_builder = 1;
        $page_data_4->breadcrumb = 1;

        $Metas_4 = [
            'title' => [$default_lang => SanitizeInput::esc_html('Demo Meta Title')],
            'description' => [$default_lang => SanitizeInput::esc_html('Demo meta desc')],
            'image' => null,
            //twitter
            'tw_image' => null,
            'tw_title' => SanitizeInput::esc_html('tw title'),
            'tw_description' => SanitizeInput::esc_html('tw desc'),
            //facebook
            'fb_image' => null,
            'fb_title' =>  SanitizeInput::esc_html('fb title'),
            'fb_description' =>  SanitizeInput::esc_html('fb desc'),
        ];

        $page_data_4->save();
        $page_data_4->metainfo()->create($Metas_4);


        $page_data_5 = new Page();
        $page_data_5->slug = Str::slug('contact','-',$default_lang);
        $page_data_5->setTranslation('title',$default_lang, SanitizeInput::esc_html('Contact'));
        $page_data_5->setTranslation('title',$default_lang_ar, SanitizeInput::esc_html('اتصال'));
        $page_data_5->setTranslation('page_content',$default_lang, 'contact content');
        $page_data_5->visibility = 0;
        $page_data_5->status = 1;
        $page_data_5->navbar_variant = '01';
        $page_data_5->footer_variant = '01';
        $page_data_5->page_builder = 1;
        $page_data_5->breadcrumb = 1;

        $Metas_5 = [
            'title' => [$default_lang => SanitizeInput::esc_html('Demo Meta Title')],
            'description' => [$default_lang => SanitizeInput::esc_html('Demo meta desc')],
            'image' => null,
            //twitter
            'tw_image' => null,
            'tw_title' => SanitizeInput::esc_html('tw title'),
            'tw_description' => SanitizeInput::esc_html('tw desc'),
            //facebook
            'fb_image' => null,
            'fb_title' =>  SanitizeInput::esc_html('fb title'),
            'fb_description' =>  SanitizeInput::esc_html('fb desc'),
        ];

        $page_data_5->save();
        $page_data_5->metainfo()->create($Metas_5);
    }


}
