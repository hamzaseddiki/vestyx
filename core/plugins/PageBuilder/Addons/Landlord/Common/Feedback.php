<?php

namespace Plugins\PageBuilder\Addons\Landlord\Common;

use App\Facades\GlobalLanguage;
use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;

use App\Models\Testimonial;
use Plugins\PageBuilder\Fields\IconPicker;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\Number;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\Slider;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\PageBuilder\PageBuilderBase;

class Feedback extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Landlord/common/feedback.png';
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
            $output .= $this->admin_language_tab_content_end();
        }
        $output .= $this->admin_language_tab_end(); //have to end language tab

        $output .= Select::get([
            'name' => 'order_by',
            'label' => __('Order By'),
            'options' => [
                'id' => __('ID'),
                'created_at' => __('Date'),
            ],
            'value' => $widget_saved_values['order_by'] ?? null,
            'info' => __('set order by')
        ]);
        $output .= Select::get([
            'name' => 'order',
            'label' => __('Order'),
            'options' => [
                'asc' => __('Accessing'),
                'desc' => __('Decreasing'),
            ],
            'value' => $widget_saved_values['order'] ?? null,
            'info' => __('set order')
        ]);


        $output .= Number::get([
            'name' => 'item_show',
            'label' => __('Item Show'),
            'value' => $widget_saved_values['item_show'] ?? null,
        ]);



        // add padding option
        $output .= $this->section_id_and_class_fields($widget_saved_values);
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
        $item_show = SanitizeInput::esc_html($this->setting_item('item_show') ?? '');
        $order_by = SanitizeInput::esc_html($this->setting_item('order_by'));
        $order = SanitizeInput::esc_html($this->setting_item('order'));
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));

        $testimonial = Testimonial::usingLocale($current_lang)->where('status',1);
        $testimonial = $testimonial->orderBy($order_by,$order);

        if(!empty($item_show)){
            $testimonial = $testimonial->take($item_show)->get();
        }else{
            $testimonial = $testimonial->take(3)->get();
        }
        $section_id = $this->setting_item('section_id') ?? '';

        $data = [
            'title'=> $title,
            'subtitle'=> $subtitle,
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
            'testimonial'=> $testimonial,
            'section_id'=> $section_id,
        ];

        return self::renderView('landlord.addons.common.feedback',$data);

    }

    public function enable(): bool
    {
        return (bool) is_null(tenant());
    }

    public function addon_title()
    {
        return __('Feedback');
    }
}
