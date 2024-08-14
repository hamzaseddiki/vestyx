<?php


namespace Plugins\WidgetBuilder\Widgets;

use App\Facades\GlobalLanguage;
use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Fields\Textarea;
use Plugins\WidgetBuilder\WidgetBase;

class TenantNewsNewsletterWidget extends WidgetBase
{
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
                'name' => 'heading_text_'.$lang->slug,
                'label' => __('Heading Text'),
                'value' => $widget_saved_values['heading_text_'.$lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'button_text_'.$lang->slug,
                'label' => __('Button Text'),
                'value' => $widget_saved_values['button_text_'.$lang->slug] ?? null,
            ]);
            $output .= $this->admin_language_tab_content_end();
        }

        $output .= $this->admin_language_tab_end(); //have to end language tab


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
        $submit_button_text = $settings['button_text_'.$user_selected_language] ?? '';
        $email_text = __("Your Email Address");
 return <<<HTML
  <div class="newspaper_sideWidget__single">
        <div class="newspaper_section__title style-02 border__bottom text-left">
            <h4 class="title">{$widget_title}</h4>
        </div>
        <div class="newspaper_sideWidget__inner mt-4">
            <div class="newspaper_sideWidget__search">
                <form action="#" class="newsletter-footer">
                    <div class="single-input">
                        <input type="text" name="email" class="form--control email" placeholder="{$email_text }">
                    </div>
                      <div class="form-message-show mt-2"></div>
                    <button type="submit" class="btn-submit radius-5 w-100 mt-3 footer_tenant_newsletter_submit">{$submit_button_text}</button>
                </form>
            </div>
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
        return __('News Sidebar Newsletter');
    }
}
