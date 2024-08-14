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

class HeaderArea extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Tenant/Consultancy/header-area.png';
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

            $output .= Text::get([
                'name' => 'button_text_'.$lang->slug,
                'label' => __('Button Text'),
                'value' => $widget_saved_values['button_text_'.$lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'button_url_'.$lang->slug,
                'label' => __('Button URL'),
                'value' => $widget_saved_values['button_url_'.$lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'bottom_title_'.$lang->slug,
                'label' => __('Bottom Title'),
                'value' => $widget_saved_values['bottom_title_'.$lang->slug] ?? null,
            ]);

            $output .= $this->admin_language_tab_content_end();
        }
        $output .= $this->admin_language_tab_end(); //have to end language tab


        $output .= Image::get([
            'name' => 'right_image',
            'label' => __('Right Image'),
            'value' => $widget_saved_values['right_image'] ?? null,
              'dimensions'=> '(375*334)'
        ]);

        $output .= Image::get([
            'name' => 'right_image_two',
            'label' => __('Right Image Two'),
            'value' => $widget_saved_values['right_image_two'] ?? null,
            'dimensions'=> '(375*334)'
        ]);

        $output .= Image::get([
            'name' => 'right_image_three',
            'label' => __('Right Image Three'),
            'value' => $widget_saved_values['right_image_three'] ?? null,
            'dimensions'=> '(375*334)'
        ]);

        $output .= Image::get([
            'name' => 'right_image_four',
            'label' => __('Right Image Four'),
            'value' => $widget_saved_values['right_image_four'] ?? null,
            'dimensions'=> '(375*334)'
        ]);

        $output .= Image::get([
            'name' => 'right_image_five',
            'label' => __('Right Image Five'),
            'value' => $widget_saved_values['right_image_five'] ?? null,
            'dimensions'=> '(375*334)'
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

        $button_text = $this->setting_item('button_text_'.$current_lang) ?? '';
        $button_url = $this->setting_item('button_url_'.$current_lang) ?? '';

        $bottom_title = $this->setting_item('bottom_title_'.$current_lang) ?? '';

        $right_image = $this->setting_item('right_image') ?? '';
        $right_image_two = $this->setting_item('right_image_two') ?? '';
        $right_image_three = $this->setting_item('right_image_three') ?? '';
        $right_image_four = $this->setting_item('right_image_four') ?? '';
        $right_image_five = $this->setting_item('right_image_five') ?? '';


        $data = [
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
            'title'=> $title,
            'description'=> $description,
            'right_image'=> $right_image,
            'right_image_two'=> $right_image_two,
            'right_image_three'=> $right_image_three,
            'right_image_four'=> $right_image_four,
            'right_image_five'=> $right_image_five,

            'button_text'=> $button_text,
            'button_url'=> $button_url,
            'bottom_title'=> $bottom_title,
        ];

        return self::renderView('tenant.consultancy.header-area',$data);
    }

    public function enable(): bool
    {
        return (bool) !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Header (Consultancy)');
    }
}
