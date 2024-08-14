<?php

namespace Plugins\PageBuilder\Addons\Landlord\Home;

use App\Facades\GlobalLanguage;
use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\ColorPicker;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\Switcher;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Fields\Textarea;
use Plugins\PageBuilder\PageBuilderBase;

class HeaderStyleOne extends PageBuilderBase
{

    public function preview_image()
    {
       return 'Landlord/home/header-01.png';
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
                'info' => __('To show the highlighted text, place your word between this code {h}YourText{/h}')
            ]);

            $output .= Textarea::get([
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
                'name' => 'button_url_'.$lang->slug,
                'label' => __('Button Url'),
                'value' => $widget_saved_values['button_url_'.$lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'right_text_'.$lang->slug,
                'label' => __('Right Text'),
                'value' => $widget_saved_values['right_text_'.$lang->slug] ?? null,
            ]);

            $output .= $this->admin_language_tab_content_end();
        }

        $output .= $this->admin_language_tab_end();

        $output .= Switcher::get([
            'name' => 'default_bg',
            'label' => __('Default Background Show/Hide'),
            'value' => $widget_saved_values['default_bg'] ?? null,
        ]);

        $output .= ColorPicker::get([
            'name' => 'bg_color',
            'label' => __('Custom Background Color'),
            'value' => $widget_saved_values['bg_color'] ?? null,
        ]);

        $output .= Image::get([
            'name' => 'shape_image',
            'label' => __(' Shape Image'),
            'value' => $widget_saved_values['shape_image'] ?? null,
            'dimensions' => __('42*41')
        ]);

        $output .= Image::get([
            'name' => 'right_image',
            'label' => __('Right Image'),
            'value' => $widget_saved_values['right_image'] ?? null,
            'dimensions' => __('628 * 548')
        ]);


        //add padding option
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
        $button_url = $this->setting_item('button_url_'.$current_lang) ?? '';
        $right_text = $this->setting_item('right_text_'.$current_lang) ?? '';

        $shape_image = $this->setting_item('shape_image') ?? '';
        $right_image = $this->setting_item('right_image') ?? '';
        $default_bg = $this->setting_item('default_bg') ?? '';
        $bg_color = $this->setting_item('bg_color') ?? '';

        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));

        $data = [
            'title'=> $title,
            'subtitle'=> $subtitle,
            'button_text'=> $button_text,
            'button_url'=> $button_url,
            'right_text'=> $right_text,
            'shape_image'=> $shape_image,
            'right_image'=> $right_image,
            'default_bg'=> $default_bg,
            'bg_color'=> $bg_color,
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
        ];

        return self::renderView('landlord.addons.home.header',$data);
    }

    public function enable(): bool
    {
        return (bool) is_null(tenant());
    }

    public function addon_title()
    {
        return __('Header :01');
    }

}
