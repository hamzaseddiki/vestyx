<?php

namespace Plugins\PageBuilder\Addons\Tenants\Job;
use App\Facades\GlobalLanguage;
use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Fields\Textarea;
use Plugins\PageBuilder\PageBuilderBase;
use function __;

class HeaderThree extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Tenant/Job/header-03.png';
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
                'name' => 'bottom_left_text_'.$lang->slug,
                'label' => __('Bottom Left Text'),
                'value' => $widget_saved_values['bottom_left_text_'.$lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'bottom_left_job_qty_'.$lang->slug,
                'label' => __('Bottom Left Job Quantity'),
                'value' => $widget_saved_values['bottom_left_job_qty_'.$lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'bottom_right_text_'.$lang->slug,
                'label' => __('Bottom Right Text'),
                'value' => $widget_saved_values['bottom_right_text_'.$lang->slug] ?? null,
            ]);

            $output .= $this->admin_language_tab_content_end();
        }
        $output .= $this->admin_language_tab_end(); //have to end language tab


        $output .= Image::get([
            'name' => 'bg_image',
            'label' => __('Background Image'),
            'value' => $widget_saved_values['bg_image'] ?? null,
             'dimensions'=> __('(1920*1022)')
        ]);

        $output .= Image::get([
            'name' => 'right_image',
            'label' => __('Right Image'),
            'value' => $widget_saved_values['right_image'] ?? null,
             'dimensions'=> __('(526*681)')
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

        $bottom_left_text = $this->setting_item('bottom_left_text_'.$current_lang) ?? '';
        $bottom_left_job_qty = $this->setting_item('bottom_left_job_qty_'.$current_lang) ?? '';
        $bottom_right_text = $this->setting_item('bottom_right_text_'.$current_lang) ?? '';

        $bg_image = $this->setting_item('bg_image') ?? '';
        $right_image = $this->setting_item('right_image') ?? '';


        $data = [
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
            'title'=> $title,
            'description'=> $description,
            'bg_image'=> $bg_image,
            'right_image'=> $right_image,
            'button_text'=> $button_text,
            'bottom_left_text'=> $bottom_left_text,
            'bottom_left_job_qty'=> $bottom_left_job_qty,
            'bottom_right_text'=> $bottom_right_text,
        ];

        return self::renderView('tenant.job.header',$data);
    }

    public function enable(): bool
    {
        return (bool) !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Header (Job)');
    }
}
