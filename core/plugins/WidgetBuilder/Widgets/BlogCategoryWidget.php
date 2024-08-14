<?php


namespace Plugins\WidgetBuilder\Widgets;

use App\Helpers\SanitizeInput;
use App\Models\Language;
use Illuminate\Support\Str;
use Modules\Blog\Entities\BlogCategory;
use Plugins\PageBuilder\Fields\Number;
use Plugins\WidgetBuilder\WidgetBase;
use function __;
use function get_user_lang;

class BlogCategoryWidget extends WidgetBase
{

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

        //render language tab
        $output .= $this->admin_language_tab();
        $output .= $this->admin_language_tab_start();

        $all_languages = Language::all();
        foreach ($all_languages as $key => $lang) {
            $output .= $this->admin_language_tab_content_start([
                'class' => $key == 0 ? 'tab-pane fade show active' : 'tab-pane fade',
                'id' => "nav-home-" . $lang->slug
            ]);
            $widget_title = $widget_saved_values['widget_title_' . $lang->slug] ?? '';
            $output .= '<div class="form-group"> <label>' .__('Widget Title').' </label><input type="text" name="widget_title_' . $lang->slug . '" class="form-control" placeholder="' . __('Widget Title') . '" value="' . $widget_title . '"></div>';

            $output .= $this->admin_language_tab_content_end();
        }
        $output .= $this->admin_language_tab_end();
        //end multi langual tab option

        $output .= Number::get([
            'name' => 'category_items',
            'label' => __('Category Items'),
            'value' => $widget_saved_values['category_items'] ?? null,
        ]);

        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        $settings = $this->get_settings();
        $user_selected_language = get_user_lang();
        $widget_title = SanitizeInput::esc_html($settings['widget_title_' . $user_selected_language] ?? '');
        $category_items = $settings['category_items'] ?? '';
        $blog_categories = BlogCategory::where('status',1)->orderBy('id', 'DESC')->take($category_items)->get();
        $category_markup = '';
        foreach ($blog_categories as $item){

            $title = $item->getTranslation('title',$user_selected_language);
            $url = route(route_prefix().'frontend.blog.category', ['id' => $item->id,'any' => $title]);


 $category_markup.= <<<LIST
    <li class="single-item"><a href="{$url}" class="wrap">{$title}</a></li>
LIST;

}

 return <<<HTML
    <div class="widget sidebar-widget">
        <div class="category style-02 v-02">
            <h4 class="widget-title style-04">{$widget_title}</h4>
            <ul class="widget-category-list">
                {$category_markup}
            </ul>
        </div>
    </div>



HTML;
    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }



    public function widget_title()
    {
        return __('Blog Category : 01');
    }
}
