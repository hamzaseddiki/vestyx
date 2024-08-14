<?php

namespace Plugins\PageBuilder\Addons\Landlord\Common;

use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Textarea;
use Plugins\PageBuilder\PageBuilderBase;

class RawHtml extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Landlord/common/raw-html.png';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();

        $widget_saved_values = $this->get_settings();

            $output .= Textarea::get([
                'name' => 'description',
                'label' => __('Description'),
                'value' => $widget_saved_values['description'] ?? null,
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
        $description = $this->setting_item('description') ?? '';
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));

        $data = [
            'description'=> $description,
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
        ];

        return self::renderView('landlord.addons.common.raw-html',$data);
    }


    public function addon_title()
    {
        return __('Html');
    }
}
