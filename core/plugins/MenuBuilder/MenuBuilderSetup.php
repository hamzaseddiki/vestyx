<?php


namespace Plugins\MenuBuilder;


class MenuBuilderSetup extends MenuBuilderBase
{
     public static function Instance(){
        return new self();
    }

    public static function multilang(){
        return true;
    }


    public function  static_pages_list()
    {
        // TODO: Implement static_pages_list() method.
        return [];
    }

    function register_dynamic_menus()
    {
        // TODO: Implement register_dynamic_menus() method.
        return [

            'pages' => [
                'model' => 'App\Models\Page',
                'name' => 'pages_page_[lang]_name',
                'route' => 'tenant.dynamic.page',
                'route_params' => ['slug'],
                'title_param' => 'title',
                'query' => 'no_lang', //old_lang|new_lang,
                'support' => 0 //0=all,1=only landlord , 2= only tenant
            ],
            'blogs' => [
                'model' => 'Modules\Blog\Entities\Blog',
                'name' => 'blog_page_[lang]_name',
                'route' => 'tenant.frontend.blog.single',
                'route_params' => ['id','slug'],
                'title_param' => 'title',
                'query' => 'no_lang' ,//old_lang|new_lang,
                'support' => 2 //0=all,1=only landlord , 2= only tenant
            ],
            'events' => [
                'model' => 'Modules\Event\Entities\Event',
                'name' => 'event_page_[lang]_name',
                'route' => 'tenant.frontend.event.single',
                'route_params' => ['id','slug'],
                'title_param' => 'title',
                'query' => 'no_lang' ,//old_lang|new_lang,
                'support' => 2 //0=all,1=only landlord , 2= only tenant
            ],

            'donations' => [
                'model' => 'Modules\Donation\Entities\Donation',
                'name' => 'donation_page_[lang]_name',
                'route' => 'tenant.frontend.donation.single',
                'route_params' => ['id','slug'],
                'title_param' => 'title',
                'query' => 'no_lang' ,//old_lang|new_lang,
                'support' => 2 //0=all,1=only landlord , 2= only tenant
            ],

            'jobs' => [
                'model' => 'Modules\Job\Entities\Job',
                'name' => 'donation_page_[lang]_name',
                'route' => 'tenant.frontend.job.single',
                'route_params' => ['id','slug'],
                'title_param' => 'title',
                'query' => 'no_lang' ,//old_lang|new_lang,
                'support' => 2 //0=all,1=only landlord , 2= only tenant
            ],

            'knowledgebase' => [
                'model' => 'Modules\Knowledgebase\Entities\Knowledgebase',
                'name' => 'donation_page_[lang]_name',
                'route' => 'tenant.frontend.knowledgebase.single',
                'route_params' => ['id','slug'],
                'title_param' => 'title',
                'query' => 'no_lang' ,//old_lang|new_lang,
                'support' => 2 //0=all,1=only landlord , 2= only tenant
            ],
        ];
    }

}
