<?php


namespace Plugins\WidgetBuilder\Widgets;

use App\Facades\GlobalLanguage;
use App\Helpers\SanitizeInput;
use Illuminate\Support\Str;
use Modules\Service\Entities\ServiceCategory;
use Plugins\PageBuilder\Fields\Number;
use Plugins\PageBuilder\Fields\Text;
use Plugins\WidgetBuilder\WidgetBase;
use function __;
use function get_user_lang;

class TenantMapWidget extends WidgetBase
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
                'name' => 'title_'.$lang->slug,
                'label' => __('Title'),
                'value' => $widget_saved_values['title_' . $lang->slug] ?? null,
            ]);

            $output .= $this->admin_language_tab_content_end();
        }

        $output .= $this->admin_language_tab_end(); //have to end language tab

        $output .= Text::get([
            'name' => 'location',
            'label' => __('Location'),
            'value' => $widget_saved_values['location'] ?? null,
        ]);

        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        $settings = $this->get_settings();
        $lang = get_user_lang();
        $widget_title = SanitizeInput::esc_html($settings['title_' . $lang] ?? '');
        $location = SanitizeInput::esc_html($settings['location'] ?? '');

        $location =  sprintf(
            '<iframe frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q=%s&amp;t=m&amp;z=%d&amp;output=embed&amp;iwloc=near" aria-label="%s"></iframe>',
            rawurlencode($location),
            10,
            $location
        );


$output = $this->widget_before();
$output .= <<<HTML

        <div class="photography_footer_widget widget">
            <h4 class="photography_widget_title fw-500">{$widget_title}</h4>
            <div class="photography_footer_inner mt-4">
                <div class="photography_footer__map">
                  {$location}
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


    public function widget_title()
    {
        return __('Google Map');
    }
}
