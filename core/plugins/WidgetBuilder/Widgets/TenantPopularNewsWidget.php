<?php


namespace Plugins\WidgetBuilder\Widgets;

use App\Facades\GlobalLanguage;
use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use App\Models\Language;
use Modules\Blog\Entities\Blog;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\Number;
use Plugins\PageBuilder\Fields\Summernote;
use Plugins\PageBuilder\Fields\Text;
use Plugins\WidgetBuilder\WidgetBase;
use Illuminate\Support\Str;
use Mews\Purifier\Facades\Purifier;

class TenantPopularNewsWidget extends WidgetBase
{


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
                'name' => 'heading_text_'.$lang->slug,
                'label' => __('Heading Text'),
                'value' => $widget_saved_values['heading_text_' . $lang->slug] ?? null,
            ]);

            $output .= $this->admin_language_tab_content_end();

        }

        $output .= $this->admin_language_tab_end(); //have to end language tab

        $output .= Number::get([
            'name' => 'blog_items',
            'label' => __('Blog Items'),
            'value' => $widget_saved_values['blog_items'] ?? null,
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
        $widget_title = SanitizeInput::esc_html($settings['heading_text_' . $user_selected_language] ?? '');
        $blog_items = $settings['blog_items'] ?? '';

        $blog_posts = Blog::where(['status' => 1])->take($blog_items)->orderBy('views','desc')->get();

        $blogs_markup = '';
        foreach ($blog_posts as $post){

            $image = render_image_markup_by_attachment_id($post->image,'','thumb');
            $route = route(route_prefix().'frontend.blog.single',$post->slug);
            $title = Str::words($post->getTranslation('title',$user_selected_language),10);



$blogs_markup.=  <<<ITEM
    <a href="{$route}" class="footer_news__item">
        <div class="footer_news__flex d-flex">
            <div class="footer_news__thumb">
                {$image}
            </div>
            <div class="footer_news__contents">
                    <a href="">
                       <p class="footer_news__para">$title</p>
                    </a>
            </div>
        </div>
    </a>
ITEM;

}


$output = $this->widget_before();
$output .= <<<HTML
    <div class="newspaper_footer_widget widget">
        <h4 class="newspaper_widget_title fw-500">{$widget_title} </h4>
        <div class="agency_footer_inner mt-4">
            <div class="footer_news">
                {$blogs_markup}
            </div>
        </div>
    </div>
HTML;

        $output .= $this->widget_after();
        return $output;
}

    public function columnClass(){
        return 'col-lg-3 col-md-6 col-sm-6';
    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }



    public function widget_title()
    {
        return __('Popular News');
    }
}
