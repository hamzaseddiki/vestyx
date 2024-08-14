<?php

namespace Plugins\PageBuilder\Addons\Tenants\BarberShop;

use App\Facades\GlobalLanguage;
use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;

use Modules\Appointment\Entities\Appointment;
use Modules\Appointment\Entities\AppointmentCategory;
use Modules\Blog\Entities\Blog;
use Modules\Blog\Entities\BlogCategory;
use Plugins\PageBuilder\Fields\IconPicker;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\NiceSelect;
use Plugins\PageBuilder\Fields\Number;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\Slider;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\PageBuilder\PageBuilderBase;

class AppointmentArea extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Tenant/BarberShop/appointment-area.png';
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
            $output .= Text::get([
                'name' => 'button_text_'.$lang->slug,
                'label' => __('Button Text'),
                'value' => $widget_saved_values['button_text_'.$lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'bottom_button_text_'.$lang->slug,
                'label' => __('Bottom Button Text'),
                'value' => $widget_saved_values['bottom_button_text_'.$lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'bottom_button_url_'.$lang->slug,
                'label' => __('Bottom Button Url'),
                'value' => $widget_saved_values['bottom_button_url_'.$lang->slug] ?? null,
            ]);


            $output .= $this->admin_language_tab_content_end();
        }
        $output .= $this->admin_language_tab_end(); //have to end language tab


        $categories = AppointmentCategory::usingLocale(GlobalLanguage::default_slug())->where(['status' => 1])->get()->mapWithKeys(function ($item){
            return [$item->id => $item->getTranslation('title',GlobalLanguage::default_slug())];
        })->toArray();

        $output .= Select::get([
            'name' => 'category',
            'label' => __('Select Category'),
            'placeholder' => __('Select Category'),
            'options' => $categories,
            'value' => $widget_saved_values['category'] ?? null,
            'info' => __('you can select your desired appointment category or leave it empty')
        ]);

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
            'name' => 'items',
            'label' => __('Items'),
            'value' => $widget_saved_values['items'] ?? null,
            'info' => __('enter how many item you want to show in frontend'),
        ]);

        $output .= Image::get([
            'name' => 'left_small_image',
            'label' => __('Left Small Image'),
            'value' => $widget_saved_values['left_small_image'] ?? null,
            'dimensions'=> __('(70*70)')
        ]);

        $output .= Image::get([
            'name' => 'right_small_image',
            'label' => __('Right Small Image'),
            'value' => $widget_saved_values['right_small_image'] ?? null,
            'dimensions'=> __('(43*43)')
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
        $category = $this->setting_item('category') == 'Select Category' ? '' : $this->setting_item('category') ;
        $order_by = SanitizeInput::esc_html($this->setting_item('order_by'));
        $order = SanitizeInput::esc_html($this->setting_item('order'));
        $items = SanitizeInput::esc_html($this->setting_item('items'));
        $title = $this->setting_item('title_'.$current_lang);
        $button_text = $this->setting_item('button_text_'.$current_lang);
        $bottom_button_text = $this->setting_item('bottom_button_text_'.$current_lang);
        $bottom_button_url = $this->setting_item('bottom_button_url_'.$current_lang);
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));

        $left_small_image = SanitizeInput::esc_html($this->setting_item('left_small_image'));
        $right_small_image = SanitizeInput::esc_html($this->setting_item('right_small_image'));


        $blogs = Appointment::query();
        if(!empty($category)) {
            if (!empty($items)) {
                $blogs =  $blogs->where('status', 1)->where('appointment_category_id',$category)->orderBy($order_by,$order)->take($items)->get();
            } else {
                $blogs =  $blogs->where('status', 1)->where('appointment_category_id',$category)->orderBy($order_by,$order)->take(4)->get();
            }
        }else{
            $blogs = $blogs->where('status', 1)->orderBy($order_by,$order)->take(6)->get();
        }


        $data = [
            'title'=> $title,
            'button_text'=> $button_text,
            'bottom_button_text'=> $bottom_button_text,
            'bottom_button_url'=> $bottom_button_url,
            'appointments'=> $blogs,
            'left_small_image'=> $left_small_image,
            'right_small_image'=> $right_small_image,

            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
        ];

        return self::renderView('tenant.barber-shop.appointment-area',$data);

    }

    public function enable(): bool
    {
        return (bool) !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Appointment Area');
    }
}
