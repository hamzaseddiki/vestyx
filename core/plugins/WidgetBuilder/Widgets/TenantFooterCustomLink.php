<?php

namespace Plugins\WidgetBuilder\Widgets;

use App\Facades\GlobalLanguage;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Fields\Textarea;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\WidgetBuilder\WidgetBase;
use function __;
use function get_user_lang;
use function render_image_markup_by_attachment_id;
use function url;

class TenantFooterCustomLink extends WidgetBase
{
    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

        $output .= $this->admin_language_tab(); //have to start language tab from here on
        $output .= $this->admin_language_tab_start();

        $all_languages = GlobalLanguage::all_languages();
        foreach ($all_languages as $key => $lang) {
            $output .= $this->admin_language_tab_content_start([
                'class' => $key == 0 ? 'tab-pane fade show active' : 'tab-pane fade',
                'id' => "nav-home-".$lang->slug
            ]);

            $output .= Text::get([
                'name' => 'title_'.$lang->slug ?? '',
                'label' => __('Title'),
                'value' => $widget_saved_values['title_'.$lang->slug] ?? '',
            ]);

            $output .= $this->admin_language_tab_content_end();
        }
        $output .= $this->admin_language_tab_end(); //have to end language tab

        //repeater
        $output .= Repeater::get([
            'settings' => $widget_saved_values,
            'multi_lang' => true,
            'id' => 'custom_link_widget_repeater',
            'fields' => [
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_title',
                    'label' => __('Inner Title')
                ],

                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_url',
                    'label' => __('Inner Url')
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
        $user_selected_language = get_user_lang();
        $widget_saved_values = $this->get_settings();
        $heading_title = $widget_saved_values['title_'.$user_selected_language] ?? '';
        $repeater_data = $widget_saved_values['custom_link_widget_repeater'];

    $item = '';
    foreach ($repeater_data['repeater_title_'.$user_selected_language] ?? [] as $key => $title){
        $rep_title = $title ?? '';

        $rep_url = $repeater_data['repeater_url_'.$user_selected_language][$key] ?? '';

  $item.= <<<ITEM
     <li  class="listItem wow fadeInUp" data-wow-delay="0.0s">
         <a href="{$rep_url}" class="singleLinks"> {$rep_title}</a>
     </li>
ITEM;

 }

        $output = $this->widget_before();
        $output .= <<<HTML

        <div class="footer-widget widget  mb-24">
            <div class="footer-tittle">
                <h4 class="footerTittle">{$heading_title}</h4>
                <ul class="listing">
                    {$item}
                </ul>
            </div>
        </div>
HTML;
        $output .= $this->widget_after();
        return $output;
}

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function columnClass(){
        return 'col-xxl-2 col-xl-2 col-lg-6 col-md-6 col-sm-6';
    }

    public function widget_title(){
        return __(' Footer Custom Link');
    }

}
