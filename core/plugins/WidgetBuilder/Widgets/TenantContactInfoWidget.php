<?php

namespace Plugins\WidgetBuilder\Widgets;
use App\Helpers\SanitizeInput;
use App\Models\Language;
use Plugins\WidgetBuilder\Traits\LanguageFallbackForWidgetBuilder;
use Plugins\WidgetBuilder\WidgetBase;
use Mews\Purifier\Facades\Purifier;

class TenantContactInfoWidget extends WidgetBase
{
    use LanguageFallbackForWidgetBuilder;

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

        //render language tab
        $output .= $this->admin_language_tab();
        $output .= $this->admin_language_tab_start();
        $all_languages = \App\Models\Language::all();
        foreach ($all_languages as $key => $lang) {
            $output .= $this->admin_language_tab_content_start([
                'class' => $key == 0 ? 'tab-pane fade show active' : 'tab-pane fade',
                'id' => "nav-home-" . $lang->slug
            ]);

            $widget_title = $widget_saved_values['widget_title_' . $lang->slug] ?? '';
            $location =  $widget_saved_values['location_' . $lang->slug] ?? '';
            $phone =  $widget_saved_values['phone_' . $lang->slug] ?? '';
            $email =  $widget_saved_values['email_' . $lang->slug] ?? '';

            $output .= '<div class="form-group"><input type="text" name="widget_title_' . $lang->slug . '"  class="form-control" placeholder="' . __('Widget Title') . '" value="'. SanitizeInput::esc_html($widget_title) .'"></div>';
            $output .= '<div class="form-group"><input type="text" name="location_' . $lang->slug . '" class="form-control" placeholder="' . __('Location') . '" value="'. SanitizeInput::esc_html($location) .'"></div>';
            $output .= '<div class="form-group"><input type="text" name="phone_' . $lang->slug . '"  class="form-control" placeholder="' . __('Phone') . '" value="'. SanitizeInput::esc_html($phone) .'"></div>';
            $output .= '<div class="form-group"><input type="email" name="email_' . $lang->slug . '" class="form-control" placeholder="' . __('Email') . '" value="'. SanitizeInput::esc_html($email) .'"></div>';


            $output .= $this->admin_language_tab_content_end();
        }
        $output .= $this->admin_language_tab_end();
        //end multi langual tab option

        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        // TODO: Implement frontend_render() method.
        $user_selected_language = get_user_lang();
        $widget_saved_values = $this->get_settings();
        $widget_title =  SanitizeInput::esc_html($this->setting_item('widget_title_' . $user_selected_language) ?? '');

        $location =  SanitizeInput::esc_html($this->setting_item('location_' . $user_selected_language) ?? '');
        $phone =  SanitizeInput::esc_html($this->setting_item('phone_' . $user_selected_language) ?? '');
        $email = SanitizeInput::esc_html($this->setting_item('email_' . $user_selected_language) ?? '');

        $output = $this->widget_before();
        $output .= <<<HTML

            <div class="footer-widget widget  mb-24">
                <div class="footer-tittle">
                    <h4 class="footerTittle">{$widget_title}</h4>
                    <ul class="listing-info">
                        <li class="listItem wow fadeInUp" data-wow-delay="0.0s">
                            <a href="#!" class="singleLinks2"><i class="fa-solid fa-phone icon"></i>{$phone}</a>
                        </li>
                        <li class="listItem wow fadeInUp" data-wow-delay="0.1s">
                            <a href="#!" class="singleLinks2"><i class="fa-solid fa-envelope icon"></i>{$email}</a>
                        </li>
                        <li class="listItem wow fadeInUp" data-wow-delay="0.2s">
                            <a href="#!" class="singleLinks2"><i class="fa-solid fa-location-dot icon"></i>{$location}</a>
                        </li>
                    </ul>
                </div>
            </div>

HTML;
        $output .= $this->widget_after();
        return $output;

    }

    public function enable(): bool
    {
        return (bool) !is_null(tenant());
    }

    public function columnClass(){
        return 'col-xxl-3 col-xl-3 col-lg-4 col-md-6 col-sm-8';
    }

    public function widget_title()
    {
        return __('Contact Info');
    }

}
