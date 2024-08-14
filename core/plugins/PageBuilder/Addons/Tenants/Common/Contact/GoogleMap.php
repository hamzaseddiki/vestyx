<?php

namespace Plugins\PageBuilder\Addons\Tenants\Common\Contact;
use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Slider;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\PageBuilderBase;
use function __;

class GoogleMap extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Tenant/common/google-map.png';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

        $output .= Text::get([
            'name' => 'location',
            'label' => __('Location'),
            'value' => $widget_saved_values['location'] ?? null,
        ]);

        $output .= Slider::get([
            'name' => 'map_height',
            'label' => __('Map Height'),
            'value' => $widget_saved_values['map_height'] ?? 500,
            'max' => 2000,
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
        $map_height = SanitizeInput::esc_html($this->setting_item('map_height'));
        $location = SanitizeInput::esc_html($this->setting_item('location'));

        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
        $location =  sprintf(
            '<iframe frameborder="0" scrolling="no" marginheight="0" height="'.$map_height.'px" marginwidth="0" src="https://maps.google.com/maps?q=%s&amp;t=m&amp;z=%d&amp;output=embed&amp;iwloc=near" aria-label="%s"></iframe>',
            rawurlencode($location),
            10,
            $location
        );

        $data = [
            'location'=> $location,
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
        ];

        return self::renderView('tenant.contact.google-map',$data);

    }

    public function enable(): bool
    {
        return (bool) !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Google Map');
    }
}
