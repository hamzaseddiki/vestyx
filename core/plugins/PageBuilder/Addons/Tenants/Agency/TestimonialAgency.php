<?php

namespace Plugins\PageBuilder\Addons\Tenants\Agency;
use App\Facades\GlobalLanguage;
use App\Helpers\SanitizeInput;
use App\Models\FormBuilder;
use App\Models\Testimonial;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\Slider;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Fields\Textarea;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\PageBuilder\PageBuilderBase;
use function __;

class TestimonialAgency extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Tenant/Agency/testimonial.png';
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
                'info' => __('To show the highlighted text, place your word between this code {h}YourText{/h]')
            ]);

            $output .= $this->admin_language_tab_content_end();
        }
        $output .= $this->admin_language_tab_end(); //have to end language tab


        //repeater
        $output .= Repeater::get([
            'settings' => $widget_saved_values,
            'multi_lang' => true,
            'id' => 'agency_testimonial_repeater',
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
                    'name' => 'repeater_name',
                    'label' => __('Name')
                ],

                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_designation',
                    'label' => __('Designation')
                ],

                [
                    'type' => RepeaterField::IMAGE,
                    'name' => 'repeater_image',
                    'label' => __('Image')
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
        $current_lang = get_user_lang();
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));

        $title = SanitizeInput::esc_html($this->setting_item('title_'.$current_lang)) ?? '';
        $repeater_data = $this->setting_item('agency_testimonial_repeater') ?? [];


        $data = [
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
            'title'=> $title,
            'repeater_data'=> $repeater_data,
        ];

        return self::renderView('tenant.agency.testimonial',$data);
    }

    public function enable(): bool
    {
        return (bool) !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Testimonial (Agency)');
    }
}
