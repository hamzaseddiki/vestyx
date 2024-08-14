<?php

namespace Plugins\PageBuilder\Addons\Tenants\Wedding;
use App\Facades\GlobalLanguage;
use App\Helpers\SanitizeInput;
use App\Models\FormBuilder;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\Slider;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Fields\Textarea;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\PageBuilder\PageBuilderBase;
use function __;

class MakeArea extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Tenant/Wedding/make-area.png';
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

            $output .= Textarea::get([
                'name' => 'description_'.$lang->slug,
                'label' => __('Description'),
                'value' => $widget_saved_values['description_'.$lang->slug] ?? null,
            ]);

            $output .= $this->admin_language_tab_content_end();
        }
        $output .= $this->admin_language_tab_end(); //have to end language tab


        //repeater
        $output .= '<h4 class="mb-3 text-center">'.__('Left Area').'</h4>';
        $output .= Repeater::get([
            'settings' => $widget_saved_values,
            'multi_lang' => true,
            'id' => 'wedding_make_area_repeater',
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
                    'type' => RepeaterField::IMAGE,
                    'name' => 'repeater_image',
                    'label' => __('Image')
                ],
            ]
        ]);

        //repeater
        $output .= '<h4 class="mb-3 text-center">'.__('Bottom Area').'</h4>';
        $output .= Repeater::get([
            'settings' => $widget_saved_values,
            'multi_lang' => true,
            'id' => 'wedding_make_area_counter_repeater',
            'fields' => [
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_title',
                    'label' => __('Title')
                ],
                [
                    'type' => RepeaterField::NUMBER,
                    'name' => 'repeater_number',
                    'label' => __('Number')
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'symbol',
                    'label' => __('Symbol')
                ],

            ]
        ]);


            $output .= Image::get([
                'name' => 'right_image',
                'label' => __('Right Image'),
                'value' => $widget_saved_values['right_image'] ?? null,
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
        $description = SanitizeInput::esc_html($this->setting_item('description_'.$current_lang)) ?? '';
        $right_image = SanitizeInput::esc_html($this->setting_item('right_image')) ?? '';
        $repeater_data = $this->setting_item('wedding_make_area_repeater');
        $repeater_data_two = $this->setting_item('wedding_make_area_counter_repeater');

        $data = [
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
            'repeater_data'=> $repeater_data,
            'repeater_data_two'=> $repeater_data_two,
            'title'=> $title,
            'description'=> $description,
            'right_image'=> $right_image,
        ];

        return self::renderView('tenant.wedding.make-area',$data);
    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Make Area (Wedding)');
    }
}
