<?php


namespace Plugins\WidgetBuilder\Widgets;

use App\Facades\GlobalLanguage;
use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use App\Models\Language;
use Modules\Blog\Entities\Blog;
use Modules\Event\Entities\Event;
use Modules\Job\Entities\Job;
use Modules\Knowledgebase\Entities\Knowledgebase;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\Number;
use Plugins\PageBuilder\Fields\Summernote;
use Plugins\PageBuilder\Fields\Text;
use Plugins\WidgetBuilder\WidgetBase;
use Illuminate\Support\Str;
use Mews\Purifier\Facades\Purifier;

class TenantRecentKnowledgebaseWidget extends WidgetBase
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
            'label' => __('Event Items'),
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

        $blog_posts = Knowledgebase::where(['status' => 1])->take($blog_items)->orderBy('id','desc')->get();

        $blogs_markup = '';
        foreach ($blog_posts as $post){

            $image = render_background_image_markup_by_attachment_id($post->image);
            $route = route('tenant.frontend.knowledgebase.single',$post->slug);
            $title = Str::words($post->getTranslation('title',$user_selected_language),10);
            $date = date('M d, Y',strtotime($post->deadline));


$blogs_markup.=  <<<ITEM
 <div class="popularServices-global">
    <a href="{$route}">
           <div class="itemsImg" {$image}></div>
    </a>

       <div class="itemsCaption">
           <h5><a href="{$route}" class="itemsTittle">{$title}</a></h5>
           <a href="">{$date}</a>
       </div>
   </div>
ITEM;

}


 return <<<HTML
     <div class="simplePresentCart2 mb-24">
     <h4 class="title mb-3">{$widget_title}</h4>
        {$blogs_markup}
     </div>
HTML;
}

    public function enable(): bool
    {
        return !is_null(tenant());
    }



    public function widget_title()
    {
        return __('Recent Articles');
    }
}
