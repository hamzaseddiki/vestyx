<?php

namespace Plugins\PageBuilder\Addons\Tenants\eCommerce;
use App\Facades\GlobalLanguage;
use App\Helpers\SanitizeInput;
use App\Models\FormBuilder;
use Modules\Attributes\Entities\Category;
use Modules\Blog\Entities\BlogCategory;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductCategory;
use Plugins\PageBuilder\Fields\Date;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\NiceSelect;
use Plugins\PageBuilder\Fields\Number;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\Slider;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Fields\Textarea;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\PageBuilder\PageBuilderBase;
use function __;

class ProductSlider extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Tenant/eCommerce/product-slider.png';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

        $output .= $this->admin_language_tab();
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
            $output .= $this->admin_language_tab_content_end();
        }
        $output .= $this->admin_language_tab_end(); //have to end language tab


        $categories = Category::where(['status_id' => 1])->get()->mapWithKeys(function ($item){
            return [$item->id => $item->name];
        })->toArray();

        $output .= NiceSelect::get([
            'multiple' => true,
            'name' => 'categories',
            'label' => __('Select Category'),
            'placeholder' => __('Select Category'),
            'options' => $categories,
            'value' => $widget_saved_values['categories'] ?? null,
            'info' => __('you can select your desired blog categories or leave it empty')
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


        $output .= Select::get([
            'name' => 'product_type',
            'label' => __('Product Type'),
            'options' => [
                'popular' => __('Popular'),
                'best_selling' => __('Best Selling'),
            ],
            'value' => $widget_saved_values['product_type'] ?? null,
            'info' => __('set product type'),
            'class' => 'product_type',
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
        $lang = get_user_lang();
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
        $title = SanitizeInput::esc_html($this->setting_item('title_'.$lang) ?? '');
        $button_text = SanitizeInput::esc_html($this->setting_item('button_text_'.$lang) ?? '');
        $categories_id = $this->setting_item('categories');
        $item_show = SanitizeInput::esc_html($this->setting_item('items') ?? '');
        $order_by = SanitizeInput::esc_html($this->setting_item('order_by'));
        $order = SanitizeInput::esc_html($this->setting_item('order'));
        $product_type = SanitizeInput::esc_html($this->setting_item('product_type'));

        $categories = Category::where('status_id',1);
        $products = Product::select('id', 'name', 'slug', 'price', 'sale_price', 'badge_id', 'image_id')->with('badge')->withCount("inventoryDetail")->where('status_id', 1);

        if (!empty($categories_id))
        {
            $categories = $categories->whereIn('id', $categories_id)->select('id', 'name', 'slug')->get();
            $products_id = ProductCategory::whereIn('category_id', $categories_id)->pluck('product_id')->toArray();
            $products->whereIn('id', $products_id);
        }

        if(!empty($item_show)) {
            if ($product_type == 'best_selling') {
                $products = $products->orderBy('sold_count', 'desc')->take($item_show)->get();
            } else {
                $products = $products->orderBy($order_by, $order)->take($item_show)->get();
            }
         }else{
            $products = $products->orderBy($order_by, $order)->take(6)->get();
         }

        $data = [
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
            'title' => $title,
            'button_text' => $button_text,
            'categories'=> $categories,
            'products'=> $products,
            'product_type'=> $product_type,
        ];


        return self::renderView('tenant.eCommerce.product-slider',$data);
    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Product Slider');
    }
}
