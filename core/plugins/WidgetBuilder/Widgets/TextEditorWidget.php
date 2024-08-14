<?php

namespace Plugins\WidgetBuilder\Widgets;


use App\Facades\GlobalLanguage;
use App\Helpers\LanguageHelper;
use App\Models\Language;
use App\Models\Widgets;
use Modules\Blog\Entities\BlogCategory;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\Number;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\Summernote;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Fields\Textarea;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\WidgetBuilder\WidgetBase;
use Mews\Purifier\Facades\Purifier;
use App\Helpers\SanitizeInput;

class TextEditorWidget extends WidgetBase
{
    public function admin_render()
    {

        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();

        $widget_saved_values = $this->get_settings();
        $output .= $this->admin_language_tab();
        $output .= $this->admin_language_tab_start();

        $all_languages = GlobalLanguage::all_languages(1);


        foreach ($all_languages as $key => $lang) {
            $output .= $this->admin_language_tab_content_start([
                'class' => $key == 0 ? 'tab-pane fade show active' : 'tab-pane fade',
                'id' => "nav-home-" . $lang->slug
            ]);

            $output .= Summernote::get([
                'name' => 'description_'.$lang->slug,
                'label' => __('Description'),
                'value' => $widget_saved_values['description_'.$lang->slug] ?? null
            ]);

            $output .= $this->admin_language_tab_content_end();
        }
        $output .= $this->admin_language_tab_end();

        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }


    public function frontend_render()
    {
        $user_selected_language = get_user_lang();
        $widget_saved_values = $this->get_settings();
        $description = $widget_saved_values['description_' . $user_selected_language] ?? '';


return <<<HTML


  <div class="data">
    {$description}
</div>

HTML;
}

    public function widget_title(){
        return __('Text Editor');
    }

}
