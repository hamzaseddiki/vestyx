<?php

namespace Plugins\PageBuilder\Addons\Tenants\Common\Contact;
use App\Facades\GlobalLanguage;
use App\Helpers\SanitizeInput;
use App\Models\FormBuilder;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\Slider;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\PageBuilder\PageBuilderBase;
use function __;

class ContactFormWithoutMap extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Tenant/Common/contact-form-without-map.png';
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

            $output .= $this->admin_language_tab_content_end();
        }
        $output .= $this->admin_language_tab_end(); //have to end language tab


        $output .= Select::get([
            'name' => 'custom_form_id',
            'label' => __('Custom Form'),
            'placeholder' => __('Select form'),
            'options' => FormBuilder::all()->pluck('title','id')->toArray(),
            'value' =>   $widget_saved_values['custom_form_id'] ?? []
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
        $current_lang = GlobalLanguage::default_slug();
        $title = $this->setting_item('title_'.$current_lang) ?? '';
        $custom_form_id = SanitizeInput::esc_html($this->setting_item('custom_form_id'));
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));


        $data = [
            'title'=> $title,
            'custom_form_id'=> $custom_form_id,
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
        ];
        return self::renderView('tenant.Common.contact.contact-form-without-map',$data);
    }

    public function enable(): bool
    {
        return (bool) !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Contact Form Without map');
    }
}
