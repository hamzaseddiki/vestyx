<?php

namespace Plugins\PageBuilder\Addons\Landlord\Common;

use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Number;
use Plugins\PageBuilder\Fields\Slider;
use Plugins\PageBuilder\PageBuilderBase;

class Brand extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Landlord/common/brand-01.png';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

        $output .= Number::get([
            'name' => 'item_show',
            'label' => __('Item Show'),
            'value' => $widget_saved_values['item_show'] ?? null,
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
        $item_show = SanitizeInput::esc_html($this->setting_item('item_show') ?? '');
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));

        $brands = \App\Models\Brand::where('status',1);

        if(!empty($item_show)){
            $brands = $brands->take($item_show)->get();
        }else{
            $brands = $brands->take(3)->get();
        }

        $data = [
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
            'brands'=> $brands,
        ];

        return self::renderView('landlord.addons.common.brand',$data);

    }

    public function enable(): bool
    {
        return (bool) is_null(tenant());
    }

    public function addon_title()
    {
        return __('Brand: 01');
    }
}
