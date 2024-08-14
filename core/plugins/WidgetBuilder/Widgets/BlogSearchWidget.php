<?php


namespace Plugins\WidgetBuilder\Widgets;
use App\Helpers\SanitizeInput;
use App\Models\Language;
use Plugins\WidgetBuilder\Traits\LanguageFallbackForWidgetBuilder;
use Plugins\WidgetBuilder\WidgetBase;

class BlogSearchWidget extends WidgetBase
{
    use LanguageFallbackForWidgetBuilder;

    public function admin_render()
    {
        // TODO: Implement admin_render() method.
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
            $output .= '<div class="form-group"><input type="text" name="widget_title_' . $lang->slug . '" class="form-control" placeholder="' . __('Widget Title') . '" value="' . $widget_title . '"></div>';

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

        $widget_title = SanitizeInput::esc_html($this->setting_item('widget_title_' . $user_selected_language) ?? '');

        $output = $this->widget_before(); //render widget before content

        if (!empty($widget_title)) {
            $output .= '<h4 class="widget-title">' . SanitizeInput::esc_html($widget_title) . '</h4>';
        }
        $search_text = __('Search');
        $output .='<div class=" blog-widget widget"><div class="widget_search">
                        <form action="'.route(route_prefix().'frontend.blog.search').'" method="get" class="search-form">
                            <div class="form-group">
                                <input type="text" class="form-control" name="search" placeholder="'. $search_text.'">
                            </div>
                            <button class="submit-btn form-btn-1" type="submit"><i class="las la-search"></i></button>
                        </form>
                    </div>
                    </div>';

        $output .= $this->widget_after(); // render widget after content

        return $output;
    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }


    public function widget_title()
    {
        // TODO: Implement widget_title() method.
        return __('Blog Search');
    }
}
