<?php

namespace Plugins\PageBuilder\Addons\Tenants\Common\misc;

use App\Facades\GlobalLanguage;
use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use App\Models\Faq;
use App\Models\FaqCategory;
use App\Models\ImageGallery;
use App\Models\ImageGalleryCategory;
use Plugins\PageBuilder\Fields\NiceSelect;
use Plugins\PageBuilder\Fields\Number;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\PageBuilder\PageBuilderBase;

class ImageGalleryOne extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Tenant/common/image-gallery-01.png';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

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
        $order_by = SanitizeInput::esc_html($this->setting_item('order_by'));
        $order = SanitizeInput::esc_html($this->setting_item('order'));
        $category_items = SanitizeInput::esc_html($this->setting_item('category_items'));

        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));


        $categories = ImageGalleryCategory::query();
        if(!empty($category_items)){
            $categories = $categories->select('id','title','status')->take($category_items)->get();
        }else{
            $categories = $categories->select('id','title','status')->take(4)->get();
        }

          $gallery = ImageGallery::query();
          $gallery = $gallery->where('status',1)->orderBy($order_by,$order)->get();

        $data = [
            'padding_top' => $padding_top,
            'padding_bottom' => $padding_bottom,
            'categories' => $categories,
            'gallery' => $gallery,
        ];

        return self::renderView('tenant.Common.misc.gallery-one',$data);
    }


    public function enable(): bool
    {
        return (bool) !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Image Gallery : 01');
    }

}
