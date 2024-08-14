<?php

namespace Plugins\PageBuilder\Addons\Tenants\Agency;
use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\PageBuilder\PageBuilderBase;
use function __;

class HeaderSlider extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Tenant/Agency/header.png';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();

        $widget_saved_values = $this->get_settings();

        //repeater
        $output .= Repeater::get([
            'multi_lang' => true,
            'settings' => $widget_saved_values,
            'id' => 'event_header_slider_repeater',
            'fields' => [
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_title',
                    'label' => __('Title')
                ],
                [
                    'type' => RepeaterField::TEXTAREA,
                    'name' => 'repeater_description',
                    'label' => __('Description')
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_button_text',
                    'label' => __('Button Text')
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_button_url',
                    'label' => __('Button URL')
                ],
                [
                    'type' => RepeaterField::IMAGE,
                    'name' => 'repeater_right_image',
                    'label' => __('Right Image'),
                    'dimensions'=> __('(636*788)')
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
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
        $bg_image = SanitizeInput::esc_html($this->setting_item('bg_image'));
        $repeater_data = $this->setting_item('event_header_slider_repeater') ?? [];


        $data = [
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
            'bg_image'=> $bg_image,
            'repeater_data'=> $repeater_data,
        ];

        return self::renderView('tenant.agency.header-slider',$data);
    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Header Slider (Common)');
    }
}
