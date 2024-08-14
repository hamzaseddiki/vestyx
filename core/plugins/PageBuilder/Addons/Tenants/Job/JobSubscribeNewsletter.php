<?php

namespace Plugins\PageBuilder\Addons\Tenants\Job;
use App\Facades\GlobalLanguage;
use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Fields\Textarea;
use Plugins\PageBuilder\PageBuilderBase;

class JobSubscribeNewsletter extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Tenant/Job/job-subscribe-newsletter.png';
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
                'value' => $widget_saved_values['title_'.$lang->slug] ?? null,
            ]);
            $output .= Text::get([
                'name' => 'button_placeholder_text_'.$lang->slug,
                'label' => __('Input Placeholder Text'),
                'value' => $widget_saved_values['button_placeholder_text_'.$lang->slug] ?? null,
            ]);

            $output .= $this->admin_language_tab_content_end();
        }
        $output .= $this->admin_language_tab_end(); //have to end language tab



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
        $button_placeholder_text = $this->setting_item('button_placeholder_text_'.$current_lang) ?? '';

        $data = [
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
            'title'=> $title,
            'description'=> $description,
            'button_text'=> $button_text,
            'button_placeholder_text'=> $button_placeholder_text,
        ];

        return self::renderView('tenant.job.job-subscribe-newsletter',$data);
    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Job Subscribe Newsletter');
    }
}
