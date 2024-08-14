<?php

namespace Plugins\PageBuilder\Helpers\Traits;

trait LanguageFallbackForPageBuilder
{
    public function setting_item($item){
        $settings = $this->get_settings();
        return $settings[$item] ?? null;
    }

    public function generateCacheKey(){
    $settings = $this->get_settings();
    return \Str::slug($this->addon_title()).$settings['id'].$settings['addon_page_id'];
    }
}
