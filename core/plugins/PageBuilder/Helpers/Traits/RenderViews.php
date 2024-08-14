<?php

namespace Plugins\PageBuilder\Helpers\Traits;

trait RenderViews
{
    public static function renderView($filename, $data = [], $moduleName = '')
    {
        $view_path = !empty($moduleName) ? strtolower($moduleName).'::addon-view.' : 'pagebuilder::';
        return view($view_path . $filename, compact('data'))->render();
    }
}
