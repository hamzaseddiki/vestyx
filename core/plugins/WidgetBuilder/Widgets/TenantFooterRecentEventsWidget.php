<?php


namespace Plugins\WidgetBuilder\Widgets;

use App\Facades\GlobalLanguage;
use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use App\Models\Language;
use Modules\Blog\Entities\Blog;
use Modules\Event\Entities\Event;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\Number;
use Plugins\PageBuilder\Fields\Summernote;
use Plugins\PageBuilder\Fields\Text;
use Plugins\WidgetBuilder\WidgetBase;
use Illuminate\Support\Str;
use Mews\Purifier\Facades\Purifier;

class TenantFooterRecentEventsWidget extends WidgetBase
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

        $blog_posts = Event::where(['status' => 1])->take($blog_items)->orderBy('id','desc')->get();

        $blogs_markup = '';
        foreach ($blog_posts as $post){

            $image = render_background_image_markup_by_attachment_id($post->image,'','thumb');
            $route = route('tenant.frontend.event.single',$post->slug);
            $title = Str::words($post->getTranslation('title',$user_selected_language),10);
            $available_ticket = $post->available_ticket;

$ticket_text = __("Tickets sold");
$blogs_markup.= <<<ITEM
   <div class="footerFlexItems mb-10  wow ladeInUp" data-wow-delay="0.0s">
        <div class="itemsImg"{$image}></div>
        <div class="itemsCaption">
            <h5><a href="{$route}" class="itemsTittle">{$title}</a></h5>
            <p class="itemsCap">{$available_ticket}+ {$ticket_text}</p>
        </div>
    </div>
ITEM;
}

$output = $this->widget_before();
$output .= <<<HTML
        <div class="footer-widget widget mb-24">
            <div class="footer-tittle">
                <h4 class="footerTittle">{$widget_title}</h4>
                    {$blogs_markup}
            </div>
        </div>
HTML;

        $output .= $this->widget_after();
        return $output;
}

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function columnClass(){
        return 'col-xxl-3 col-xl-3 col-lg-6 col-md-6 col-sm-12';
    }




    public function widget_title()
    {
        return __('Footer Recent Events');
    }
}
