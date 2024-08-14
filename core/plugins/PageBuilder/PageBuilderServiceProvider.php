<?php

namespace Plugins\PageBuilder;

use Illuminate\Support\ServiceProvider;

class PageBuilderServiceProvider extends ServiceProvider
{
    public function boot(){}
    public function register(){
        $this->loadViewsFrom(__DIR__.'/views','pagebuilder');
    }
}
