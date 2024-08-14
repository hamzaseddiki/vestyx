<?php

namespace Plugins\PageBuilder\Addons\Landlord\Common;

use App\Facades\GlobalLanguage;
use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;

use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\Number;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Slider;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\PageBuilder\PageBuilderBase;

class OnlyImage extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Landlord/common/only-image.png';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

        $output .= Image::get([
            'name' => 'image',
            'label' => __('Image'),
            'value' => $widget_saved_values['image'] ?? null,
        ]);

        $output .= Text::get([
            'name' => 'image_url',
            'label' => __('Image URL'),
            'value' => $widget_saved_values['image_url'] ?? null,
        ]);

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

        $image = $this->setting_item('image') ?? '';
        $image_url = $this->setting_item('image_url') ?? '';

        $data = [
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
            'image' => $image,
            'image_url' => $image_url,
        ];

        return self::renderView('landlord.addons.common.only-image',$data);

    }


    public function addon_title()
    {
        return __('Only Image');
    }
}
