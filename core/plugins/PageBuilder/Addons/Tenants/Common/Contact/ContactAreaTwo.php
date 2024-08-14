<?php

namespace Plugins\PageBuilder\Addons\Tenants\Common\Contact;
use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\PageBuilder\PageBuilderBase;
use function __;

class ContactAreaTwo extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Tenant/common/contact-02.png';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();



        //repeater
        $output .= Repeater::get([
            'settings' => $widget_saved_values,
            'id' => 'contact_tenant_repeater_two',
            'fields' => [
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_title',
                    'label' => __('Title')
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_info_one',
                    'label' => __('Info One')
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_info_two',
                    'label' => __('Info Two')
                ],
                [
                    'type' => RepeaterField::ICON_PICKER,
                    'name' => 'repeater_icon',
                    'label' => __('Icon')
                ],
            ]
        ]);

        $output .= Image::get([
            'name' => 'bg_image',
            'label' => __('Background Image '),
            'value' => $widget_saved_values['bg_image'] ?? null,
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
        $bg_image = $this->setting_item('bg_image');
        $repeater_data = $this->setting_item('contact_tenant_repeater_two');

        $data = [
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
            'bg_image'=> $bg_image,
            'repeater_data'=> $repeater_data,
        ];

        return self::renderView('tenant.contact.contact-area-two',$data);

    }

    public function enable(): bool
    {
        return (bool) !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Contact Area : 02');
    }
}
