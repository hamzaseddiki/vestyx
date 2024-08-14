<?php

namespace Plugins\PageBuilder\Addons\Tenants\BarberShop;
use App\Facades\GlobalLanguage;
use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Fields\Textarea;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\PageBuilder\PageBuilderBase;
use function __;

class HeaderArea extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Tenant/BarberShop/header-area.png';
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
                'label' => __('Button Url'),
                'value' => $widget_saved_values['button_url_'.$lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'video_text_'.$lang->slug,
                'label' => __('Video Text'),
                'value' => $widget_saved_values['video_text_'.$lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'video_url_'.$lang->slug,
                'label' => __('Video Url'),
                'value' => $widget_saved_values['video_url_'.$lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'open_text_'.$lang->slug,
                'label' => __('Open Text'),
                'value' => $widget_saved_values['open_text_'.$lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'close_text_'.$lang->slug,
                'label' => __('Close Text'),
                'value' => $widget_saved_values['close_text_'.$lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'open_time_'.$lang->slug,
                'label' => __('Open Time'),
                'value' => $widget_saved_values['open_time_'.$lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'close_time_'.$lang->slug,
                'label' => __('Close Time'),
                'value' => $widget_saved_values['close_time_'.$lang->slug] ?? null,
            ]);


            $output .= $this->admin_language_tab_content_end();
        }
        $output .= $this->admin_language_tab_end(); //have to end language tab


        $output .= Image::get([
            'name' => 'left_small_star_image',
            'label' => __('Left Small Star Image'),
            'value' => $widget_saved_values['left_small_star_image'] ?? null,
             'dimensions'=> __('(48*48)')
        ]);

        $output .= Image::get([
            'name' => 'middle_scissor_image',
            'label' => __('Middle Scissor Image'),
            'value' => $widget_saved_values['middle_scissor_image'] ?? null,
            'dimensions'=> __('(43*43)')
        ]);

        $output .= Image::get([
            'name' => 'middle_small_star_image',
            'label' => __('Middle Small Star Image'),
            'value' => $widget_saved_values['middle_small_star_image'] ?? null,
            'dimensions'=> __('(70*70)')
        ]);

        $output .= Image::get([
            'name' => 'middle_medium_text_star_image',
            'label' => __('Middle Medium Text Star Image'),
            'value' => $widget_saved_values['middle_medium_text_star_image'] ?? null,
            'dimensions'=> __('(141*140)')
        ]);

        $output .= Image::get([
            'name' => 'right_product_image',
            'label' => __('Right Product Image'),
            'value' => $widget_saved_values['right_product_image'] ?? null,
             'dimensions'=> __('(154*273)')
        ]);

        $output .= Image::get([
            'name' => 'right_shape_image',
            'label' => __('Right Shape Image'),
            'value' => $widget_saved_values['right_shape_image'] ?? null,
        ]);


        $output .= Image::get([
            'name' => 'right_barber_image',
            'label' => __('Right Barber Image'),
            'value' => $widget_saved_values['right_barber_image'] ?? null,
            'dimensions'=> __('(360*434)')
        ]);

        $output .= Image::get([
            'name' => 'right_price_image',
            'label' => __('Right Price Image'),
            'value' => $widget_saved_values['right_price_image'] ?? null,
            'dimensions'=> __('(422*180)')
        ]);


        //repeater left
        $left_anymation = __('Left Section Animation');
        $output.= "<h4 class='text-center'>".$left_anymation."</h4>";
        $output .= Repeater::get([
            'settings' => $widget_saved_values,
            'multi_lang' => true,
            'id' => 'barber_shop_banner_bottom_left_repeater',
            'fields' => [
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_title',
                    'label' => __('Title')
                ],
                [
                    'type' => RepeaterField::IMAGE,
                    'name' => 'repeater_image',
                    'label' => __('Image')
                ],
            ]
        ]);


        //repeater right
        $right_anymation = __('Right Section Animation');
        $output.= "<h4 class='text-center'>".$right_anymation."</h4>";
        $output .= Repeater::get([
            'settings' => $widget_saved_values,
            'multi_lang' => true,
            'id' => 'barber_shop_banner_bottom_right_repeater',
            'fields' => [
                [
                    'type' => RepeaterField::TEXT,
                    'name' => 'repeater_title',
                    'label' => __('Title')
                ],
                [
                    'type' => RepeaterField::IMAGE,
                    'name' => 'repeater_image',
                    'label' => __('Image')
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

        $button_text = $this->setting_item('button_text_'.$current_lang) ?? '';
        $button_url = $this->setting_item('button_url_'.$current_lang) ?? '';

        $video_text = $this->setting_item('video_text_'.$current_lang) ?? '';
        $video_url = $this->setting_item('video_url_'.$current_lang) ?? '';

        $open_text = $this->setting_item('open_text_'.$current_lang) ?? '';
        $close_text = $this->setting_item('close_text_'.$current_lang) ?? '';

        $open_time = $this->setting_item('open_time_'.$current_lang) ?? '';
        $close_time = $this->setting_item('close_time_'.$current_lang) ?? '';


        $left_small_star_image = $this->setting_item('left_small_star_image') ?? '';
        $middle_scissor_image = $this->setting_item('middle_scissor_image') ?? '';
        $middle_small_star_image = $this->setting_item('middle_small_star_image') ?? '';

        $middle_medium_text_star_image = $this->setting_item('middle_medium_text_star_image') ?? '';
        $right_product_image = $this->setting_item('right_product_image') ?? '';
        $right_shape_image = $this->setting_item('right_shape_image') ?? '';
        $right_barber_image = $this->setting_item('right_barber_image') ?? '';
        $right_price_image = $this->setting_item('right_price_image') ?? '';

        $barber_shop_banner_bottom_left_repeater = $this->setting_item('barber_shop_banner_bottom_left_repeater') ?? [];
        $barber_shop_banner_bottom_right_repeater = $this->setting_item('barber_shop_banner_bottom_right_repeater') ?? [];



        $data = [
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
            'title'=> $title,
            'description'=> $description,
            'button_text'=> $button_text,
            'button_url'=> $button_url,
            'video_text'=> $video_text,
            'video_url'=> $video_url,
            'open_text'=> $open_text,
            'close_text'=> $close_text,
            'open_time'=> $open_time,
            'close_time'=> $close_time,

            'left_small_star_image'=> $left_small_star_image,
            'middle_scissor_image'=> $middle_scissor_image,
            'middle_small_star_image'=> $middle_small_star_image,
            'middle_medium_text_star_image'=> $middle_medium_text_star_image,
            'right_product_image'=> $right_product_image,
            'right_shape_image'=> $right_shape_image,
            'right_barber_image'=> $right_barber_image,
            'right_price_image'=> $right_price_image,

            'barber_shop_banner_bottom_left_repeater'=> $barber_shop_banner_bottom_left_repeater,
            'barber_shop_banner_bottom_right_repeater'=> $barber_shop_banner_bottom_right_repeater,

        ];

        return self::renderView('tenant.barber-shop.header-area',$data);
    }

    public function enable(): bool
    {
        return (bool) !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Header (Barber Shop)');
    }
}
