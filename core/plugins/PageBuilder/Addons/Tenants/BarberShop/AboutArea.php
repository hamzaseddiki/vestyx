<?php

namespace Plugins\PageBuilder\Addons\Tenants\BarberShop;
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
        return 'Tenant/BarberShop/about-area.png';
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
                'info' => __('To show the highlighted text, place your word between this code {h}YourText{/h]')
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
                'name' => 'author_name_'.$lang->slug,
                'label' => __('Author Name'),
                'value' => $widget_saved_values['author_name_'.$lang->slug] ?? null,
            ]);


            $output .= $this->admin_language_tab_content_end();
        }
        $output .= $this->admin_language_tab_end(); //have to end language tab


        $output .= Image::get([
            'name' => 'left_image_one',
            'label' => __('Left Image One'),
            'value' => $widget_saved_values['left_image_one'] ?? null,
            'dimensions' => __('(440*273)')
        ]);

        $output .= Image::get([
            'name' => 'left_image_two',
            'label' => __('Left Image Two'),
            'value' => $widget_saved_values['left_image_two'] ?? null,
            'dimensions' => __('(440*273)')
        ]);

        $output .= Image::get([
            'name' => 'author_image',
            'label' => __('Author Image'),
            'value' => $widget_saved_values['author_image'] ?? null,
            'dimensions' => __('(60*60)')
        ]);

        $output .= Image::get([
            'name' => 'shape_image',
            'label' => __('Shape Image'),
            'value' => $widget_saved_values['shape_image'] ?? null,
            'dimensions' => __('(141*140)')
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

        $button_text = SanitizeInput::esc_html($this->setting_item('button_text_'.$current_lang)) ?? '';
        $button_url = SanitizeInput::esc_html($this->setting_item('button_url_'.$current_lang)) ?? '';
        $author_name = SanitizeInput::esc_html($this->setting_item('author_name_'.$current_lang)) ?? '';

        $left_image_one = $this->setting_item('left_image_one') ?? '';
        $left_image_two = $this->setting_item('left_image_two') ?? '';
        $author_image = $this->setting_item('author_image') ?? '';
        $shape_image = $this->setting_item('shape_image') ?? '';

        $data = [
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
            'title'=> $title,
            'description'=> $description,
            'button_text'=> $button_text,
            'button_url'=> $button_url,
            'author_name'=> $author_name,

            'left_image_one'=> $left_image_one,
            'left_image_two'=> $left_image_two,
            'author_image'=> $author_image,
            'shape_image'=> $shape_image,
        ];

        return self::renderView('tenant.barber-shop.about-area',$data);
    }

    public function enable(): bool
    {
        return (bool) !is_null(tenant());
    }

    public function addon_title()
    {
        return __('About Us (Barber Shop)');
    }
}
