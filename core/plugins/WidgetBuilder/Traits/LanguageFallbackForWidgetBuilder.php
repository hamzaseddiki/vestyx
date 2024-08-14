<?php

namespace Plugins\WidgetBuilder\Traits;

trait LanguageFallbackForWidgetBuilder
{
    public function setting_item($item){
        $settings = $this->get_settings();
        return $settings[$item] ?? null;
    }
}
