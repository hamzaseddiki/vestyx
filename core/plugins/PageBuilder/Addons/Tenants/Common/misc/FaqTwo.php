<?php

namespace Plugins\PageBuilder\Addons\Tenants\Common\misc;

use App\Facades\GlobalLanguage;
use App\Helpers\SanitizeInput;
use App\Models\Faq;
use App\Models\FaqCategory;
use Plugins\PageBuilder\Fields\Number;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\PageBuilderBase;

class FaqTwo extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Tenant/common/faq-02.png';
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
                'name' => 'heading_title'. $lang->slug,
                'label' => __('Heading'),
                'value' => $widget_saved_values['heading_title'. $lang->slug] ?? null,
                'info' => __('To show the highlighted text, place your word between this code {h}YourText{/h}')
            ]);

            $output .= Text::get([
                'name' => 'left_title-' . $lang->slug,
                'label' => __('Left Title'),
                'value' => $widget_saved_values['left_title-'. $lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'button_text_'. $lang->slug,
                'label' => __('Button Text'),
                'value' => $widget_saved_values['button_text_'. $lang->slug] ?? null,
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
            'name' => 'category_items',
            'label' => __('Category Items'),
            'value' => $widget_saved_values['category_items'] ?? null,
            'info' => __('enter how many item you want to show in frontend'),
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
        $heading_title = $this->setting_item('heading_title' . $current_lang) ?? '';
        $left_title = $this->setting_item('left_title-'. $current_lang) ?? '';
        $button_text = $this->setting_item('button_text_'. $current_lang) ?? '';

        $order_by = SanitizeInput::esc_html($this->setting_item('order_by'));
        $order = SanitizeInput::esc_html($this->setting_item('order'));
        $category_items = SanitizeInput::esc_html($this->setting_item('category_items'));

        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));


        $categories = FaqCategory::query();
        if(!empty($category_items)){
            $categories = $categories->select('id','title','status')->take($category_items)->get();
        }else{
            $categories = $categories->select('id','title','status')->take(4)->get();
        }

        $faq = Faq::query();
         $faq = $faq->where('status',1)->orderBy($order_by,$order)->get();

        $data = [
            'padding_top' => $padding_top,
            'padding_bottom' => $padding_bottom,
            'heading_title' => $heading_title,
            'left_title' => $left_title,
            'button_text' => $button_text,
            'categories' => $categories,
            'faq' => $faq,
        ];

        return self::renderView('tenant.Common.misc.faq-two',$data);
    }


    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Faq : 02');
    }

}
