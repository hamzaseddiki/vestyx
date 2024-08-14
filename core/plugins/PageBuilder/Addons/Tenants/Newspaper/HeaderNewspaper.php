<?php

namespace Plugins\PageBuilder\Addons\Tenants\Newspaper;
use App\Facades\GlobalLanguage;
use App\Helpers\SanitizeInput;
use App\Models\FormBuilder;
use Modules\Blog\Entities\Blog;
use Modules\Blog\Entities\BlogCategory;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\Number;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\PageBuilderBase;
use function __;

class HeaderNewspaper extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Tenant/Newspaper/header.png';
    }
    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

        $categories = BlogCategory::usingLocale(GlobalLanguage::default_slug())->where(['status' => 1])->get()->mapWithKeys(function ($item){
            return [$item->id => $item->getTranslation('title',GlobalLanguage::default_slug())];
        })->toArray();

        $blogs = Blog::usingLocale(GlobalLanguage::default_slug())->where(['status' => 1])->get()->mapWithKeys(function ($item){
            return [$item->id => $item->getTranslation('title',GlobalLanguage::default_slug())];
        })->toArray();

        $output .= Select::get([
            'name' => 'blog_id',
            'label' => __('Select left Blog'),
            'placeholder' => __('Select Left Blog'),
            'options' => $blogs,
            'value' => $widget_saved_values['blog_id'] ?? null,
        ]);

        $output .= Select::get([
            'name' => 'categories',
            'label' => __('Select Right Category'),
            'placeholder' => __('Select Right Blog Category'),
            'options' => $categories,
            'value' => $widget_saved_values['categories'] ?? null,
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
            'label' => __('Right Blog Items'),
            'value' => $widget_saved_values['items'] ?? null,
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
        $category = $this->setting_item('categories') == 'Select Category' ? '' : $this->setting_item('categories') ;
        $blog_id = $this->setting_item('blog_id') ;
        $order_by = SanitizeInput::esc_html($this->setting_item('order_by'));
        $order = SanitizeInput::esc_html($this->setting_item('order'));
        $items = SanitizeInput::esc_html($this->setting_item('items'));
        $title = $this->setting_item('title_'.$current_lang);
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));



        $blogs = Blog::query();
        if(!empty($category)) {

            if (!empty($items)) {
                $blogs =  $blogs->where('status', 1)->where('category_id',$category)->orderBy($order_by,$order)->take($items)->get();
            } else {
                $blogs =  $blogs->where('status', 1)->where('category_id',$category)->orderBy($order_by,$order)->take(4)->get();
            }
        }else{
            $blogs = $blogs->where('status', 1)->orderBy($order_by,$order)->get(4);
        }


        $left_blog = [];
        if(!empty($blog_id)){
            $left_blog = Blog::where('id',$blog_id)->first();
        }else{
            $left_blog = Blog::first();
        }

        $data = [
            'title'=> $title,
            'left_blog'=> $left_blog,
            'blogs'=> $blogs,
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
        ];

        return self::renderView('tenant.newspaper.header',$data);

    }


    public function enable(): bool
    {
        return (bool) !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Header (Newspaper)');
    }
}
