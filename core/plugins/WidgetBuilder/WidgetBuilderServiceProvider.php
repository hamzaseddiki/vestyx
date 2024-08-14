<?php

namespace Plugins\WidgetBuilder;

use Illuminate\Support\ServiceProvider;

class WidgetBuilderServiceProvider extends ServiceProvider
{
    public function boot(){}
    public function register(){
        $this->loadViewsFrom(__DIR__.'/views','widgetbuilder');
    }
}
