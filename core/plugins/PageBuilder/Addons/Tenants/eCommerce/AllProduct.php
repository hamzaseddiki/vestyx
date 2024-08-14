<?php

namespace Plugins\PageBuilder\Addons\Tenants\eCommerce;
use App\Facades\GlobalLanguage;
use App\Helpers\SanitizeInput;
use Modules\Attributes\Entities\Category;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductCategory;
use Plugins\PageBuilder\Fields\NiceSelect;
use Plugins\PageBuilder\Fields\Number;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\PageBuilderBase;
use function __;

class AllProduct extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Tenant/eCommerce/all-product.png';
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
                'name' => 'deal_title_'.$lang->slug,
                'label' => __('Deals Title'),
                'value' => $widget_saved_values['deal_title_'.$lang->slug] ?? null,
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

        $products = Product::where(['status_id' => 1])->get()->mapWithKeys(function ($item){
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

        $output .= NiceSelect::get([
            'name' => 'product',
            'label' => __('Select Product For Best Deal'),
            'placeholder' => __('Select Product'),
            'options' => $products,
            'value' => $widget_saved_values['product'] ?? null,
            'info' => __('you can select your desired product for deal from here')
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

        $output .= Number::get([
            'name' => 'category_items',
            'label' => __('Items'),
            'value' => $widget_saved_values['category_items'] ?? null,
            'info' => __('enter how many category item you want to show in frontend'),
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
        $deal_title = SanitizeInput::esc_html($this->setting_item('deal_title_'.$lang) ?? '');
        $categories_id = $this->setting_item('categories');
        $product_id = $this->setting_item('product');
        $item_show = SanitizeInput::esc_html($this->setting_item('items') ?? '');
        $category_item_show = SanitizeInput::esc_html($this->setting_item('category_items') ?? '');
        $order_by = SanitizeInput::esc_html($this->setting_item('order_by'));
        $order = SanitizeInput::esc_html($this->setting_item('order'));


        $categories = Category::where('status_id',1);
        $products = Product::with('badge')->where('status_id', 1);


        if(!empty($category_item_show)){
            $all_categories = Category::select('id','name','slug')->where('status_id',1)->take($category_item_show)->get();
        }else{
            $all_categories = Category::select('id','name','slug')->where('status_id',1)->take(10)->get();
        }

        $deal_product = [];
        if(!empty($product_id)){
            $deal_product = Product::find($product_id);
        }else{
            $deal_product = Product::first();
        }

        if (!empty($categories_id))
        {
            $categories = $categories->whereIn('id', $categories_id)->select('id', 'name', 'slug')->get();
            $products_id = ProductCategory::whereIn('category_id', $categories_id)->pluck('product_id')->toArray();
            $products->with('badge')->whereIn('id', $products_id);
        }

        if(!empty($item_show)){
            $products = $products->orderBy($order_by,$order)->take($item_show)->get() ?? [];
        }else{
            $products = $products->orderBy($order_by,$order)->take(6)->get() ?? [];
        }

        $data = [
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
            'title' => $title,
            'button_text' => $button_text,
            'deal_title' => $deal_title,
            'categories'=> $categories,
            'products'=> $products,
            'all_categories'=> $all_categories,
            'deal_product'=> $deal_product,
            'product_limit'=> $item_show,
        ];


        return self::renderView('tenant.eCommerce.all-product',$data);
    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function addon_title()
    {
        return __('All Product');
    }
}
