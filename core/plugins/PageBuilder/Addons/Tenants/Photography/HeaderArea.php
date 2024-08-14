<?php

namespace Plugins\PageBuilder\Addons\Tenants\Photography;
use App\Facades\GlobalLanguage;
use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Fields\Textarea;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\PageBuilder\PageBuilderBase;

class HeaderArea extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Tenant/Photography/header-area.png';
    }

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
                'id' => "nav-home-" . $lang->slug
            ]);
            $output .= Text::get([
                'name' => 'title_'.$lang->slug,
                'label' => __('Title'),
                'value' => $widget_saved_values['title_'.$lang->slug] ?? null,
            ]);

            $output .= Textarea::get([
                'name' => 'description_'.$lang->slug,
                'label' => __('Description'),
                'value' => $widget_saved_values['description_'.$lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'left_button_text_'.$lang->slug,
                'label' => __('Left Button Text'),
                'value' => $widget_saved_values['left_button_text_'.$lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'left_button_url_'.$lang->slug,
                'label' => __('Left Button URL'),
                'value' => $widget_saved_values['left_button_url_'.$lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'video_button_text_'.$lang->slug,
                'label' => __('Video Button Text'),
                'value' => $widget_saved_values['video_button_text_'.$lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'video_url_'.$lang->slug,
                'label' => __('Video URL'),
                'value' => $widget_saved_values['video_url_'.$lang->slug] ?? null,
            ]);


            $output .= $this->admin_language_tab_content_end();
        }
        $output .= $this->admin_language_tab_end(); //have to end language tab


        //repeater
        $output .= Repeater::get([
            'settings' => $widget_saved_values,
            'multi_lang' => false,
            'id' => 'photography_header_repeater',
            'fields' => [
                [
                    'type' => RepeaterField::IMAGE,
                    'name' => 'repeater_image',
                    'label' => __('Right Image'),
                    'dimensions' => '(386*478)'
                ],

            ],

        ]);

        // add padding option
        $output .= $this->padding_fields($widget_saved_values);
        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        $current_lang = get_user_lang();

        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));

        $title = $this->setting_item('title_'.$current_lang) ?? '';
        $description = $this->setting_item('description_'.$current_lang) ?? '';

        $left_button_text = $this->setting_item('left_button_text_'.$current_lang) ?? '';
        $left_button_url = $this->setting_item('left_button_url_'.$current_lang) ?? '';

        $video_button_text = $this->setting_item('video_button_text_'.$current_lang) ?? '';
        $video_url = $this->setting_item('video_url_'.$current_lang) ?? '';

        $repeater_data = $this->setting_item('photography_header_repeater');


        $data = [
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
            'title'=> $title,
            'description'=> $description,
            'left_button_text'=> $left_button_text,
            'left_button_url'=> $left_button_url,
            'video_button_text'=> $video_button_text,
            'video_url'=> $video_url,
            'repeater_data'=> $repeater_data,
        ];

        return self::renderView('tenant.photography.header-area',$data);
    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Header (Photography)');
    }
}
