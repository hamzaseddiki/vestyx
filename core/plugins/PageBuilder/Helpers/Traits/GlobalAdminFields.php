<?php

namespace Plugins\PageBuilder\Helpers\Traits;

use Plugins\PageBuilder\Fields\Slider;
use Plugins\PageBuilder\Fields\Text;

trait GlobalAdminFields
{
    public function padding_fields($widget_saved_values = []){
        $output = '';
        $output .= Slider::get([
            'name' => 'padding_top',
            'label' => __('Padding Top'),
            'value' => $widget_saved_values['padding_top'] ?? 110,
            'max' => 300,
        ]);
        $output .= Slider::get([
            'name' => 'padding_bottom',
            'label' => __('Padding Bottom'),
            'value' => $widget_saved_values['padding_bottom'] ?? 110,
            'max' => 300,
        ]);

        return $output;
    }
    public function section_id_and_class_fields($widget_saved_values = []){


        $output = '';
        $output .= Text::get([
            'name' => 'section_id',
            'label' => __('Section ID'),
            'value' => $widget_saved_values['section_id'] ?? '',
        ]);
        $output .= Text::get([
            'name' => 'section_class',
            'label' => __('Section Class'),
            'value' => $widget_saved_values['section_class'] ?? '',
        ]);

        return $output;
    }

}
