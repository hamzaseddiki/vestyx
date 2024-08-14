<?php


namespace Plugins\PageBuilder\Addons\Landlord\Common;


use App\Facades\GlobalLanguage;
use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\Slider;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Helpers\Traits\RepeaterHelper;
use Plugins\PageBuilder\PageBuilderBase;


class VideoArea extends PageBuilderBase
{
    use RepeaterHelper;

    public function preview_image()
    {
        return 'Landlord/common/video-area.png';
    }
    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

        $output .= $this->admin_language_tab(); //have to start language tab from here on
        $output .= $this->admin_language_tab_start();

        $all_languages = GlobalLanguage::all_languages(1);

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

        $output .= \Plugins\PageBuilder\Fields\Text::get([
            'name' => 'video_url',
            'label' => __('Video URL'),
            'value' => $widget_saved_values['video_url'] ?? '',
            'placeholder' => 'https://www.youtube.com/watch?v=wernkluer',
        ]);
        $output .= Image::get([
            'name' => 'video_background_image',
            'label' => __('Background Image'),
            'value' => $widget_saved_values['video_background_image'] ?? '',
            'dimensions' => '1067×586px'
        ]);

        $output .= Slider::get([
            'name' => 'padding_top',
            'label' => __('Padding Top'),
            'value' => $widget_saved_values['padding_top'] ?? 60,
            'max' => 500,
        ]);
        $output .= Slider::get([
            'name' => 'padding_bottom',
            'label' => __('Padding Bottom'),
            'value' => $widget_saved_values['padding_bottom'] ?? 60,
            'max' => 500,
        ]);
        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render(): string
    {
        $lang = get_user_lang();
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
        $title = $this->setting_item('title_'.$lang);
        $video_url = SanitizeInput::esc_html($this->setting_item('video_url'));
        $video_background_image = SanitizeInput::esc_html($this->setting_item('video_background_image'));


        $data = [
            'title'=> $title,
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
            'bg_image'=> $video_background_image,
            'video_url'=> $video_url,
        ];

        return self::renderView('landlord.addons.common.video-area',$data);
    }

    public function addon_title()
    {
        return __('Video Area');
    }

}
