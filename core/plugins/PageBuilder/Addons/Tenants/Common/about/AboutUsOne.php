<?php

namespace Plugins\PageBuilder\Addons\Tenants\Common\about;
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

class AboutUsOne extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Tenant/Common/about-us-01.png';
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


        $output .= Image::get([
            'name' => 'right_image',
            'label' => __('Image'),
            'value' => $widget_saved_values['right_image'] ?? null,
            'dimensions' => __('(636*566)')
        ]);

        $output .= Select::get([
            'name' => 'image_alignment',
            'label' => __('Image Alignment'),
            'options' => [
                'left' => __('Left'),
                'right' => __('Right'),
            ],
            'value' => $widget_saved_values['image_alignment'] ?? null,
            'info' => __('Set image position or alignment from here')
        ]);

        //repeater
        $output .= Repeater::get([
            'settings' => $widget_saved_values,
            'multi_lang' => true,
            'id' => 'about_us_repeater',
            'fields' => [
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_title',
                    'label' => __('Title')
                ],
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_url',
                    'label' => __('URL')
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

        $title = $this->setting_item('title_'.$current_lang) ?? '';
        $description = $this->setting_item('description_'.$current_lang) ?? '';
        $right_image = $this->setting_item('right_image') ?? '';
        $image_alignment = $this->setting_item('image_alignment') ?? '';
        $repeater_data = $this->setting_item('about_us_repeater');

        $data = [
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
            'repeater_data'=> $repeater_data,
            'title'=> $title,
            'description'=> $description,
            'right_image'=> $right_image,
            'image_alignment'=> $image_alignment,
        ];

        return self::renderView('tenant.Common.about.about-us',$data);
    }

    public function enable(): bool
    {
        return (bool) !is_null(tenant());
    }

    public function addon_title()
    {
        return __('About Us : 01');
    }
}
