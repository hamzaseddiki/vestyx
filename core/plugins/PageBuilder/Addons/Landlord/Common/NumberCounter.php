<?php

namespace Plugins\PageBuilder\Addons\Landlord\Common;

use App\Facades\GlobalLanguage;
use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;

use App\Models\Testimonial;
use Plugins\PageBuilder\Fields\IconPicker;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\Number;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Slider;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\PageBuilder\PageBuilderBase;

class NumberCounter extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Landlord/common/brand.png';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

        $all_languages = GlobalLanguage::all_languages();

        $output .= Repeater::get([
            'multi_lang' => true,
            'settings' => $widget_saved_values,
            'id' => 'counter_repeater',
            'fields' => [
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_title',
                    'label' => __('Counter Title')
                ],
                [
                    'type' => RepeaterField::NUMBER,
                    'name' => 'repeater_number',
                    'label' => __('Counter Number')
                ],
            ]
        ]);

        // add padding option
        $output .= $this->section_id_and_class_fields($widget_saved_values);
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

        $repeater_data = $this->setting_item('counter_repeater');

        $section_id = $this->setting_item('section_id') ?? '';
        $data = [
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
            'repeater_data' => $repeater_data,
            'section_id'=> $section_id,
        ];

        return self::renderView('landlord.addons.common.number_counter',$data);

    }

    public function enable(): bool
    {
        return (bool) is_null(tenant());
    }

    public function addon_title()
    {
        return __('Number Counter: 01');
    }
}
