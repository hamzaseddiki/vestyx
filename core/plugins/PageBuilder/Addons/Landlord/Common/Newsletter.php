<?php

namespace Plugins\PageBuilder\Addons\Landlord\Common;

use App\Facades\GlobalLanguage;
use App\Helpers\SanitizeInput;

use App\Models\Testimonial;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\Number;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\PageBuilderBase;

class Newsletter extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Landlord/common/newsletter.png';
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

            $output .= Text::get([
                'name' => 'button_text_'.$lang->slug,
                'label' => __('Button Text'),
                'value' => $widget_saved_values['button_text_'.$lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'input_text_'.$lang->slug,
                'label' => __('Input Text'),
                'value' => $widget_saved_values['input_text_'.$lang->slug] ?? null,
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
        $current_lang = GlobalLanguage::user_lang_slug();
        $title = $this->setting_item('title_'.$current_lang) ?? '';
        $subtitle = $this->setting_item('subtitle_'.$current_lang) ?? '';

        $button_text = $this->setting_item('button_text_'.$current_lang) ?? '';
        $input_text = $this->setting_item('input_text_'.$current_lang) ?? '';

        $bg_image = $this->setting_item('bg_shape') ?? '';

        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));

        $section_id = $this->setting_item('section_id') ?? '';

        $data = [
            'title'=> $title,
            'subtitle'=> $subtitle,
            'button_text'=> $button_text,
            'input_text'=> $input_text,
            'bg_image'=> $bg_image,
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
            'section_id'=> $section_id,
        ];

        return self::renderView('landlord.addons.common.newsletter',$data);

    }

    public function enable(): bool
    {
        return (bool) is_null(tenant());
    }

    public function addon_title()
    {
        return __('Newsletter');
    }
}
