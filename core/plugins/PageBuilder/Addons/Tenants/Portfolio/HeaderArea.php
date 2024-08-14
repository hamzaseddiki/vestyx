<?php

namespace Plugins\PageBuilder\Addons\Tenants\Portfolio;
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
        return 'Tenant/Portfolio/header-area.png';
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
                'name' => 'top_title_'.$lang->slug,
                'label' => __('Top Title'),
                'value' => $widget_saved_values['top_title_'.$lang->slug] ?? null,
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
                'name' => 'button_text_'.$lang->slug,
                'label' => __('Button Text'),
                'value' => $widget_saved_values['button_text_'.$lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'button_url_'.$lang->slug,
                'label' => __('Button URL'),
                'value' => $widget_saved_values['button_url_'.$lang->slug] ?? null,
            ]);



            $output .= $this->admin_language_tab_content_end();
        }
        $output .= $this->admin_language_tab_end(); //have to end language tab


        $output .= Image::get([
            'name' => 'right_image',
            'label' => __('Right Image'),
            'value' => $widget_saved_values['right_image'] ?? null,
        ]);


        //repeater
        $output .= Repeater::get([
            'settings' => $widget_saved_values,
            'multi_lang' => true,
            'id' => 'portfolio_counter_area_repeater',
            'fields' => [
                [
                    'type' => RepeaterField::NUMBER,
                    'name' => 'repeater_number',
                    'label' => __('Number')
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_extra',
                    'label' => __('Extra')
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_title',
                    'label' => __('Title')
                ],


            ]
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

        $top_title = $this->setting_item('top_title_'.$current_lang) ?? '';
        $title = $this->setting_item('title_'.$current_lang) ?? '';
        $description = $this->setting_item('description_'.$current_lang) ?? '';

        $button_text = $this->setting_item('button_text_'.$current_lang) ?? '';
        $button_url = $this->setting_item('button_url_'.$current_lang) ?? '';
        $right_image = $this->setting_item('right_image') ?? '';

        $repeater_data = $this->setting_item('portfolio_counter_area_repeater') ?? '';

        $data = [
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
            'top_title'=> $top_title,
            'title'=> $title,
            'description'=> $description,
            'button_text'=> $button_text,
            'button_url'=> $button_url,
            'right_image'=> $right_image,
            'repeater_data'=> $repeater_data,
        ];

        return self::renderView('tenant.portfolio.header-area',$data);
    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }
    public function addon_title()
    {
        return __('Header (Portfolio)');
    }
}
