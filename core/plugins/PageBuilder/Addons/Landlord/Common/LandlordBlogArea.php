<?php

namespace Plugins\PageBuilder\Addons\Landlord\Common;
use App\Facades\GlobalLanguage;
use App\Helpers\SanitizeInput;
use Modules\Blog\Entities\Blog;
use Modules\Blog\Entities\BlogCategory;
use Plugins\PageBuilder\Fields\ColorPicker;
use Plugins\PageBuilder\Fields\Number;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\Switcher;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\PageBuilderBase;

class LandlordBlogArea extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Landlord/common/blog-area.png';
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
                'name' => 'more_text_'.$lang->slug,
                'label' => __('More Text'),
                'value' => $widget_saved_values['more_text_'.$lang->slug] ?? null,
            ]);


            $output .= $this->admin_language_tab_content_end();
        }
        $output .= $this->admin_language_tab_end(); //have to end language tab

        $categories = BlogCategory::usingLocale(GlobalLanguage::default_slug())->where(['status' => 1])->get()->mapWithKeys(function ($item){
            return [$item->id => $item->getTranslation('title',GlobalLanguage::default_slug())];
        })->toArray();

        $output .= Select::get([
            'name' => 'categories',
            'label' => __('Select Right Category'),
            'placeholder' => __('Select Right Blog Category'),
            'options' => $categories,
            'value' => $widget_saved_values['categories'] ?? null,
        ]);

        $output .= Number::get([
            'name' => 'items',
            'label' => __('Blog Items'),
            'value' => $widget_saved_values['items'] ?? null,
            'info' => __('enter how many item you want to show in frontend'),
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


        $output .= Switcher::get([
            'name' => 'default_bg',
            'label' => __('sdfsdfDefault Background Show/Hide'),
            'value' => $widget_saved_values['default_bg'] ?? null,
        ]);

        $output .= ColorPicker::get([
            'name' => 'bg_color',
            'label' => __('Custom Background Color'),
            'value' => $widget_saved_values['bg_color'] ?? null,
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
        $items = SanitizeInput::esc_html($this->setting_item('items'));
        $title = $this->setting_item('title_'.$current_lang);
        $more_text = $this->setting_item('more_text_'.$current_lang);
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));

        $default_bg = $this->setting_item('default_bg') ?? '';
        $bg_color = $this->setting_item('bg_color') ?? '';

        $blogs = Blog::query();
        if(!empty($category)) {

            if (!empty($items)) {
                $blogs =  $blogs->where('status', 1)->where('category_id',$category)->orderBy($order_by,$order)->paginate($items);

            } else {
                $blogs =  $blogs->where('status', 1)->where('category_id',$category)->orderBy($order_by,$order)->paginate(3);
            }
        }else{
            if(!empty($items)){
                $blogs = $blogs->where('status', 1)->orderBy($order_by,$order)->paginate($items);
            }else{
                $blogs = $blogs->where('status', 1)->orderBy($order_by,$order)->paginate(3);
            }

        }


        $data = [
            'title'=> $title,
            'more_text'=> $more_text,
            'blogs'=> $blogs,
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
            'default_bg'=> $default_bg,
            'bg_color'=> $bg_color,
        ];

        return self::renderView('landlord.addons.common.blog-area',$data);

    }

    public function enable(): bool
    {
        return (bool) is_null(tenant());
    }

    public function addon_title()
    {
        return __('Blog Area');
    }
}
