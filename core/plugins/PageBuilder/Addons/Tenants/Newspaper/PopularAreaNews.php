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
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Fields\Textarea;
use Plugins\PageBuilder\PageBuilderBase;
use function __;

class PopularAreaNews extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Tenant/Newspaper/popular-area.png';
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

            $output .= $this->admin_language_tab_content_end();
        }
        $output .= $this->admin_language_tab_end(); //have to end language tab

        $categories = BlogCategory::usingLocale(GlobalLanguage::default_slug())->where(['status' => 1])->get()->mapWithKeys(function ($item){
            return [$item->id => $item->getTranslation('title',GlobalLanguage::default_slug())];
        })->toArray();

        $blogs = Blog::usingLocale(GlobalLanguage::default_slug())->where(['status' => 1])->get()->mapWithKeys(function ($item){
            return [$item->id => $item->getTranslation('title',GlobalLanguage::default_slug())];
        })->toArray();

        $output .= Select::get([
            'name' => 'left_blog_id',
            'label' => __('Select Left News'),
            'placeholder' => __('Left News'),
            'options' => $blogs,
            'value' => $widget_saved_values['left_blog_id'] ?? null,
        ]);


        $output .= Select::get([
            'name' => 'categories',
            'label' => __('Select Middle News Category'),
            'placeholder' => __('Select Middle News Category'),
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
            'name' => 'center_items',
            'label' => __('Center News Items'),
            'value' => $widget_saved_values['center_items'] ?? null,
            'info' => __('enter how many item you want to show in frontend'),
        ]);

        $output .= Number::get([
            'name' => 'right_items',
            'label' => __('Right News Items'),
            'value' => $widget_saved_values['right_items'] ?? null,
            'info' => __('enter how many item you want to show in frontend'),
        ]);

        $output .= Number::get([
            'name' => 'category_items',
            'label' => __('Category Items'),
            'value' => $widget_saved_values['category_items'] ?? null,
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
        $order_by = SanitizeInput::esc_html($this->setting_item('order_by'));
        $order = SanitizeInput::esc_html($this->setting_item('order'));
        $left_blog_id = $this->setting_item('left_blog_id')  ?? '' ;
        $center_items = SanitizeInput::esc_html($this->setting_item('center_items'));
        $right_items = SanitizeInput::esc_html($this->setting_item('right_items'));
        $category_items = SanitizeInput::esc_html($this->setting_item('category_items'));
        $title = $this->setting_item('title_'.$current_lang);
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));

        $blogs = Blog::query();
        if(!empty($category)) {

            if (!empty($center_items)) {
                $blogs =  $blogs->where('status', 1)->where('category_id',$category)->orderBy($order_by,$order)->take($center_items)->get();
            } else {
                $blogs =  $blogs->where('status', 1)->where('category_id',$category)->orderBy($order_by,$order)->take(4)->get();
            }
        }else{
            $blogs = $blogs->where('status', 1)->orderBy($order_by,$order)->get(4);
        }
        ;

        $left_blog = [];
        if(!empty($left_blog_id)){
            $left_blog = Blog::where('id',$left_blog_id)->first();
        }else{
            $left_blog = Blog::first();
        }

        $recent_blogs = Blog::where('status', 1)->orderBy('id','desc')->take($right_items)->get();
        $trending = Blog::where('status', 1)->where('featured','on')->orderBy('id','desc')->take($right_items)->get();
        $most_viewed = Blog::where('status', 1)->orderBy('views','desc')->take($right_items)->get();

        $all_categories = BlogCategory::where('status', 1)->orderBy('id','desc')->take($category_items)->get();


        $data = [
            'title'=> $title,
            'blogs'=> $blogs,
            'left_blog'=> $left_blog,

            'recent_blogs'=> $recent_blogs,
            'trending'=> $trending,
            'most_viewed'=> $most_viewed,

            'all_categories'=> $all_categories,

            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
        ];


        return self::renderView('tenant.newspaper.popular',$data);

    }


    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Popular Area (Newspaper)');
    }
}
