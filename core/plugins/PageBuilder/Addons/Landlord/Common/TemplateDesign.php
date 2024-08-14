<?php

namespace Plugins\PageBuilder\Addons\Landlord\Common;

use App\Facades\GlobalLanguage;
use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;

use Modules\Blog\Entities\Blog;
use Plugins\PageBuilder\Fields\IconPicker;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\Slider;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\PageBuilder\PageBuilderBase;

class TemplateDesign extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Landlord/common/template-design.png';
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
                'name' => 'right_text_'.$lang->slug,
                'label' => __('Right Text'),
                'value' => $widget_saved_values['right_text_'.$lang->slug] ?? null,
                'info' => __('If you don want to show this, than please leave this empty')
            ]);
            $output .= Text::get([
                'name' => 'right_text_url_'.$lang->slug,
                'label' => __('Right Text Url'),
                'value' => $widget_saved_values['right_text_url_'.$lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'bottom_text_'.$lang->slug,
                'label' => __('Bottom Text'),
                'value' => $widget_saved_values['bottom_text_'.$lang->slug] ?? null,
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
                'is_available' => __('Availability'),
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

        $output .= Text::get([
            'name' => 'item_show',
            'label' => __('Items'),
            'value' => $widget_saved_values['item_show'] ?? null,
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
        $current_lang = GlobalLanguage::user_lang_slug();
        $title = $this->setting_item('title_'.$current_lang) ?? '';

        $right_text = $this->setting_item('right_text_'.$current_lang) ?? '';
        $right_text_url = $this->setting_item('right_text_url_'.$current_lang) ?? '';
        $bottom_text = $this->setting_item('bottom_text_'.$current_lang) ?? '';

        $items = $this->setting_item('item_show') ?? '';

        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));



        $data = [
            'title'=> $title,
            'right_text'=> $right_text,
            'right_text_url'=> $right_text_url,
            'bottom_text'=> $bottom_text,
            'items'=> $items,

            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
        ];

        return self::renderView('landlord.addons.common.template-design',$data);

    }

    public function enable(): bool
    {
        return (bool) is_null(tenant());
    }

    public function addon_title()
    {
        return __('Template Design');
    }
}
