<?php

namespace Plugins\PageBuilder\Addons\Tenants\BarberShop;
use App\Facades\GlobalLanguage;
use App\Helpers\SanitizeInput;
use App\Models\FormBuilder;
use Modules\Blog\Entities\Blog;
use Modules\Blog\Entities\BlogCategory;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\Number;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Fields\Textarea;
use Plugins\PageBuilder\PageBuilderBase;
use function __;

class ContactArea extends PageBuilderBase{

    public function preview_image()
    {
        return 'Tenant/BarberShop/contact-area.png';
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
                'name' => 'button_url_'.$lang->slug,
                'label' => __('Button URL'),
                'value' => $widget_saved_values['button_url_'.$lang->slug] ?? null,
            ]);


            $output .= $this->admin_language_tab_content_end();
        }
        $output .= $this->admin_language_tab_end(); //have to end language tab


        $output .= Image::get([
            'name' => 'top_shape_text_image',
            'label' => __('Top Text Image'),
            'value' => $widget_saved_values['top_shape_text_image'] ?? null,
            'dimensions'=> __('(141*140)')
        ]);

        $output .= Image::get([
            'name' => 'short_bottom_image',
            'label' => __('Short Bottom Image'),
            'value' => $widget_saved_values['short_bottom_image'] ?? null,
            'dimensions'=> __('(70*70)')
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
        $title = $this->setting_item('title_'.$current_lang);
        $subtitle = $this->setting_item('subtitle_'.$current_lang);
        $description = $this->setting_item('description_'.$current_lang);
        $button_text = $this->setting_item('button_text_'.$current_lang);
        $button_url = $this->setting_item('button_url_'.$current_lang);
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));

        $top_shape_text_image = $this->setting_item('top_shape_text_image');
        $short_bottom_image = $this->setting_item('short_bottom_image');

        $data = [
            'title'=> $title,
            'subtitle'=> $subtitle,
            'button_text'=> $button_text,
            'button_url'=> $button_url,
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
            'top_shape_text_image'=> $top_shape_text_image,
            'short_bottom_image'=> $short_bottom_image,
        ];

        return self::renderView('tenant.barber-shop.contact-area',$data);

    }


    public function enable(): bool
    {
        return (bool) !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Discount Area (Barber)');
    }
}
