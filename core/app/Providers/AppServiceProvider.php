<?php

namespace App\Providers;

use App\Helpers\LanguageHelper;
use App\Helpers\ModuleMetaData;
use App\Helpers\SidebarMenuHelper;
use App\Helpers\TenantHelper\TenantHelpers;
use App\Helpers\ThemeMetaData;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Modules\EmailTemplate\Helpers\EmailTemplateHelper;

class AppServiceProvider extends ServiceProvider
{

    public function register()
    {
        app()->singleton('LandlordAdminMenu',function (){
           return  new SidebarMenuHelper();
        });
        app()->singleton('GlobalLanguage',function (){
           return  new LanguageHelper();
        });

        app()->singleton('EmailTemplate',function (){
            return new EmailTemplateHelper();
        });

        $this->app->singleton('ThemeDataFacade', function (){
            return new ThemeMetaData();
        });

        $this->app->singleton('ModuleDataFacade', function (){
            return new ModuleMetaData();
        });
//        $this->app->singleton('TenantHelpers', function (){
//            return new TenantHelpers();
//        });
    }

    public function boot()
    {
        Paginator::useBootstrap();
        Schema::defaultStringLength(191);

        // if (get_static_option('site_force_ssl_redirection') === 'on'){
            URL::forceScheme('https');
        // }
    }
}
