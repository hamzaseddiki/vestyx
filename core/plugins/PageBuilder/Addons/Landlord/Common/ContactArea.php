<?php

namespace Plugins\PageBuilder\Addons\Landlord\Common;

use App\Facades\GlobalLanguage;
use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use App\Models\FormBuilder;
use Plugins\PageBuilder\Fields\Number;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\Slider;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\PageBuilder\PageBuilderBase;

class ContactArea extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Landlord/common/contact-form.png';
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

        $output .= Text::get([
            'name' => 'location',
            'label' => __('Location'),
            'value' =>   $widget_saved_values['location'] ?? ''
        ]);

        $output .= Number::get([
            'name' => 'map_height',
            'label' => __('Map Height'),
            'value' =>   $widget_saved_values['map_height'] ?? '',
            'info' => __('Height is in px')
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
        $current_lang = GlobalLanguage::user_lang_slug();
        $title = $this->setting_item('title_'.$current_lang) ?? '';
        $custom_form_id = SanitizeInput::esc_html($this->setting_item('custom_form_id'));
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));

        $location = SanitizeInput::esc_html($this->setting_item('location'));
        $map_height = SanitizeInput::esc_html($this->setting_item('map_height'));

        $repeater_data = $this->setting_item('contact_repeater');
        $section_id = $this->setting_item('section_id');

        $location =  sprintf(
            '<iframe frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q=%s&amp;t=m&amp;z=%d&amp;output=embed&amp;iwloc=near" aria-label="%s"></iframe>',
            rawurlencode($location),
            10,
            $location
        );

        $data = [
            'title'=> $title,
            'custom_form_id'=> $custom_form_id,
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
            'repeater_data'=> $repeater_data,
            'location' => $location,
            'map_height' => $map_height,
        ];

        return self::renderView('landlord.addons.common.contact',$data);

    }

    public function enable(): bool
    {
        return (bool) is_null(tenant());
    }

    public function addon_title()
    {
        return __('Contact Area');
    }
}
