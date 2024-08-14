<?php

namespace Plugins\PageBuilder\Addons\Tenants\Consultancy;
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

class AboutArea extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Tenant/Consultancy/about-area.png';
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

            $output .= Text::get([
                'name' => 'subtitle_'.$lang->slug,
                'label' => __('Subtitle'),
                'value' => $widget_saved_values['subtitle_'.$lang->slug] ?? null,
            ]);

            $output .= Textarea::get([
                'name' => 'description_'.$lang->slug,
                'label' => __('Description'),
                'value' => $widget_saved_values['description_'.$lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'left_button_text_'.$lang->slug,
                'label' => __('Left Button Text'),
                'value' => $widget_saved_values['left_button_text_'.$lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'left_button_url_'.$lang->slug,
                'label' => __('Left Button URL'),
                'value' => $widget_saved_values['left_button_url_'.$lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'right_button_text_'.$lang->slug,
                'label' => __('Right Button Text'),
                'value' => $widget_saved_values['right_button_text_'.$lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'right_button_url_'.$lang->slug,
                'label' => __('Right Button URL'),
                'value' => $widget_saved_values['right_button_url_'.$lang->slug] ?? null,
            ]);

            $output .= Textarea::get([
                'name' => 'bottom_description_'.$lang->slug,
                'label' => __('Bottom Description'),
                'value' => $widget_saved_values['bottom_description_'.$lang->slug] ?? null,
            ]);

            $output .= $this->admin_language_tab_content_end();
        }
        $output .= $this->admin_language_tab_end(); //have to end language tab


        $output .= Image::get([
            'name' => 'left_image',
            'label' => __('Left Image'),
            'value' => $widget_saved_values['left_image'] ?? null,
            'dimensions' => __('(536*479)')
        ]);

        $output .= Image::get([
            'name' => 'right_image',
            'label' => __('Right Image'),
            'value' => $widget_saved_values['right_image'] ?? null,
            'dimensions' => __('(536*479)')
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
        $subtitle = SanitizeInput::esc_html($this->setting_item('subtitle_'.$current_lang)) ?? '';
        $description = SanitizeInput::esc_html($this->setting_item('description_'.$current_lang)) ?? '';
        $bottom_description = SanitizeInput::esc_html($this->setting_item('bottom_description_'.$current_lang)) ?? '';

        $left_button_text = SanitizeInput::esc_html($this->setting_item('left_button_text_'.$current_lang)) ?? '';
        $left_button_url = SanitizeInput::esc_html($this->setting_item('left_button_url_'.$current_lang)) ?? '';

        $right_button_text = SanitizeInput::esc_html($this->setting_item('right_button_text_'.$current_lang)) ?? '';
        $right_button_url = SanitizeInput::esc_html($this->setting_item('right_button_url_'.$current_lang)) ?? '';

        $left_image = $this->setting_item('left_image') ?? '';
        $right_image = $this->setting_item('right_image') ?? '';

        $data = [
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
            'title'=> $title,
            'subtitle'=> $subtitle,

            'left_button_text'=> $left_button_text,
            'left_button_url'=> $left_button_url,

            'right_button_text'=> $right_button_text,
            'right_button_url'=> $right_button_url,

            'description'=> $description,
            'bottom_description'=> $bottom_description,
            'left_image'=> $left_image,
            'right_image'=> $right_image,
        ];

        return self::renderView('tenant.consultancy.about-area',$data);
    }

    public function enable(): bool
    {
        return (bool) !is_null(tenant());
    }

    public function addon_title()
    {
        return __('About Us (Consultancy)');
    }
}
