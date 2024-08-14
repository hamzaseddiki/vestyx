<?php


namespace Plugins\WidgetBuilder\Widgets;

use App\Facades\GlobalLanguage;
use App\Helpers\SanitizeInput;
use Illuminate\Support\Str;
use Modules\Donation\Entities\DonationActivity;
use Plugins\PageBuilder\Fields\Number;
use Plugins\PageBuilder\Fields\Text;
use Plugins\WidgetBuilder\WidgetBase;

class TenantRecentDonationActivitiesWidget extends WidgetBase
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
            'label' => __('Activities Items'),
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

        $blog_posts = DonationActivity::where(['status' => 1])->take($blog_items)->orderBy('id','desc')->get();

        $markup = '';
        foreach ($blog_posts as $post){

            $image = render_background_image_markup_by_attachment_id($post->image,'','thumb');
            $route = route('tenant.frontend.donation.activities.single',$post->slug);
            $title = Str::words($post->getTranslation('title',$user_selected_language),10);
            $description = Str::words(purify_html_raw($post->getTranslation('description',$user_selected_language)),15);

            $text = __('View Story');


$markup.=  <<<ITEM
  <div class="singleFlexitem2 mb-10">
        <div class="activitiesImg" {$image}></div>
        <div class="activitiesCaption">
            <h5><a href="{$route}" class="featureTittle">{$title}</a></h5>
            <p class="featureCap">{$description}</p>
            <div class="btn-wrapper mt-10">
                <a href="{$route}" class="browseBtn">{$text}</a>
            </div>
        </div>
    </div>
ITEM;

}


 return <<<HTML
     <div class="simplePresentCart2 mb-30">
        <div class="small-tittle mb-40">
            <h3 class="tittle lineStyleOne">{$widget_title}</h3>
        </div>
        {$markup}
    </div>
HTML;
}

    public function enable(): bool
    {
        return !is_null(tenant());
    }



    public function widget_title()
    {
        return __('Donation Activities');
    }
}
