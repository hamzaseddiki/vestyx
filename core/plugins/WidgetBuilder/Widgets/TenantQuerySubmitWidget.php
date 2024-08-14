<?php


namespace Plugins\WidgetBuilder\Widgets;

use App\Facades\GlobalLanguage;
use App\Helpers\SanitizeInput;
use Plugins\PageBuilder\Fields\Text;
use Plugins\WidgetBuilder\WidgetBase;

class TenantQuerySubmitWidget extends WidgetBase
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
                'value' => $widget_saved_values['heading_text_' . $lang->slug] ?? null,
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

        $route_action = route('tenant.frontend.query.submit');
        $csrf = csrf_token();
        $email_label = __('Email');
        $subject_label = __('Subject');
        $message_label = __('Message');
        $submit_button_text = __('Sent message');

        $class_con = get_static_option('tenant_default_theme') == 'job-find' ?  '2' : '';
$email_text = __("Enter email");
$subject_text = __("Enter subject");
$message_text = __("write anything");

 return <<<HTML
    <div class="simplePresentCart{$class_con} cart mb-24">
        <div class="small-tittle mb-30">
            <h3 class="tittle">{$widget_title}</h3>
        </div>
        <form action="{$route_action}" class="contactUs query_form" method="post">
            <div class="row">
            <div class="query_form_message_show"></div>
                <div class="col-lg-12 col-md-12">
                    <label class="catTittle"> {$email_label}</label>
                    <div class="input-form input-form2">
                        <input type="text" placeholder="{$email_text}" name="email">
                    </div>
                </div>
                <div class="col-lg-12 col-md-12">
                    <label class="catTittle">{$subject_label}</label>
                    <div class="input-form input-form2">
                        <input type="text" placeholder="{$subject_text}" name="subject">
                    </div>
                </div>
                <div class="col-lg-12 col-md-12">
                    <label class="catTittle">{$message_label}</label>
                    <div class="input-form input-form2">
                        <textarea placeholder="{$message_text}" name="message"></textarea>
                    </div>
                </div>

                <div class="col-sm-12">
                    <div class="btn-wrapper mb-10">
                        <button type="submit" class="cmn-btn1 hero-btn query_submit_button">{$submit_button_text}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
HTML;
}

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function widget_title()
    {
        return __('Query Submit');
    }
}
