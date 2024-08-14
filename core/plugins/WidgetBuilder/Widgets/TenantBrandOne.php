<?php

namespace Plugins\WidgetBuilder\Widgets;


use App\Helpers\LanguageHelper;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\WidgetBuilder\Traits\LanguageFallbackForWidgetBuilder;
use Plugins\WidgetBuilder\WidgetBase;
use function __;
use function get_user_lang;
use function render_image_markup_by_attachment_id;
use function url;

class TenantBrandOne extends WidgetBase
{
    use LanguageFallbackForWidgetBuilder;
    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();


        //repeater
        $output .= Repeater::get([
            'settings' => $widget_saved_values,
            'id' => 'tenant_brand_widget_one',
            'fields' => [
                [
                    'type' => RepeaterField::IMAGE,
                    'name' => 'repeater_image',
                    'label' => __('Image')
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_image_url',
                    'label' => __('Image URL')
                ],

            ]
        ]);

        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {

        $repeader_data = $this->setting_item('tenant_brand_widget_one') ?? [];


        $brand_markup = '';
        foreach ($repeader_data['repeater_image_url_'] ?? [] as $key => $url)
        {
            $repeater_url = $url ?? '';
            $repeater_image = render_image_markup_by_attachment_id($repeader_data['repeater_image_'][$key] ?? '');

            $brand_markup.= <<<MARKUP
            <div class="download-link margin-bottom-20">
                    <a href="{$repeater_url}">
                       {$repeater_image}
                    </a>
                </div>
            MARKUP;
        }


return <<<HTML
 <div class="col-lg-2 col-md-6 col-sm-6">
            <div class="footer-widget widget widget_nav_menu wow animate__animated animate__fadeInUp animated">
                {$brand_markup}
            </div>
        </div>
HTML;
}

    public function enable(): bool
    {
        return !is_null(tenant()) ? true : false;
    }

    public function widget_title(){
        return __('Brand : 01');
    }

}
