<?php

namespace Plugins\PageBuilder\Addons\Tenants\SoftwareBusiness;

use App\Facades\GlobalLanguage;
use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use App\Models\Testimonial;
use Modules\Blog\Entities\Blog;
use Modules\Blog\Entities\BlogCategory;
use Modules\Service\Entities\Service;
use Modules\Service\Entities\ServiceCategory;
use Plugins\PageBuilder\Fields\IconPicker;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\NiceSelect;
use Plugins\PageBuilder\Fields\Number;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Fields\Textarea;
use Plugins\PageBuilder\PageBuilderBase;
use function __;

class ServiceArea extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Tenant/SoftwareBusiness/service-area.png';
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
                'name' => 'extra_text_'.$lang->slug,
                'label' => __('Extra Text'),
                'value' => $widget_saved_values['extra_text_'.$lang->slug] ?? null,
            ]);

            $output .= $this->admin_language_tab_content_end();
        }
        $output .= $this->admin_language_tab_end(); //have to end language tab

        $categories = ServiceCategory::usingLocale(GlobalLanguage::default_slug())->where(['status' => 1])->get()->mapWithKeys(function ($item){
            return [$item->id => $item->getTranslation('title',GlobalLanguage::default_slug())];
        })->toArray();

        $output .= Select::get([
            'name' => 'categories',
            'label' => __('Select Category'),
            'placeholder' => __('Select Category'),
            'options' => $categories,
            'value' => $widget_saved_values['categories'] ?? null,
            'info' => __('you can select your desired service categories or leave it empty')
        ]);


        $output .= Number::get([
            'name' => 'item',
            'label' => __('Item'),
            'value' => $widget_saved_values['item'] ?? null,
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


        //add padding option
        $output .= $this->padding_fields($widget_saved_values);
        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        $items = SanitizeInput::esc_html($this->setting_item('item'));
        $category = $this->setting_item('categories') == 'Select Category' ? '' : $this->setting_item('categories') ;
        $lang = get_user_lang();
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
        $order_by = SanitizeInput::esc_html($this->setting_item('order_by'));
        $order = SanitizeInput::esc_html($this->setting_item('order'));

        $title = SanitizeInput::esc_html($this->setting_item('title_'.$lang));
        $button_text = SanitizeInput::esc_html($this->setting_item('button_text_'.$lang));
        $button_url = SanitizeInput::esc_html($this->setting_item('button_url_'.$lang));
        $extra_text = SanitizeInput::esc_html($this->setting_item('extra_text_'.$lang));

        $service = Service::query();
        if(!empty($category)) {

            if (!empty($items)) {
                $service =  $service->where('status', 1)->where('category_id',$category)->orderBy($order_by,$order)->paginate($items);
            } else {
                $service =  $service->where('status', 1)->where('category_id',$category)->orderBy($order_by,$order)->paginate(4);
            }
        }else{
            $service = $service->where('status', 1)->orderBy($order_by,$order)->paginate(6);
        }


        $data = [
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
            'service'=> $service,
            'title'=> $title,
            'button_text'=> $button_text,
            'button_url'=> $button_url,
            'extra_text'=> $extra_text,
        ];

        return self::renderView('tenant.software-business.service-area',$data);

    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Service Area (Software)');
    }
}
