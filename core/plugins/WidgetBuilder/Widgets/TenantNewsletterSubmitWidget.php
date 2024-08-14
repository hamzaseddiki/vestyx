<?php


namespace Plugins\WidgetBuilder\Widgets;

use App\Facades\GlobalLanguage;
use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Fields\Textarea;
use Plugins\WidgetBuilder\WidgetBase;

class TenantNewsletterSubmitWidget extends WidgetBase
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

            $output .= Textarea::get([
                'name' => 'description_'.$lang->slug,
                'label' => __('Description'),
                'value' => $widget_saved_values['description_'.$lang->slug] ?? null,
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
        $description = SanitizeInput::esc_html($settings['description_' . $user_selected_language] ?? '');
        $submit_button_text = __('Subscribe');

        $output = $this->widget_before();
        $email_text = __("Your Email Address");
        $output .= <<<HTML

        <div class="footer-widget widget  mb-24">
            <div class="footer-tittle">
                <h4 class="footerTittle">{$widget_title}</h4>
                <div class="footer-form mt-10 wow fadeInRight" data-wow-delay="0.2s" >
                    <div class="form-row mb-20">
                        <form class="newsletter-footer" target="_blank" action="" method="post">

                            <input class="input email" type="email" name="email" placeholder="{$email_text }">
                            <div class="btn-wrapper form-icon">
                              <div class="form-message-show mt-2"></div>
                                <button type="submit" class="btn-default btn-rounded footer_tenant_newsletter_submit"  >{$submit_button_text}</button>
                            </div>
                            <div class="footer-pera mt-20">
                                <p class="pera">{$description}</p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

HTML;

        $output .= $this->widget_after();
        return $output;

}

    public function columnClass(){
        return 'col-xxl-3 col-xl-3 col-lg-6';
    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function widget_title()
    {
        return __('Footer Newsletter');
    }
}
