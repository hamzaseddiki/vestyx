<?php


namespace Plugins\WidgetBuilder\Widgets;

use App\Facades\GlobalLanguage;
use App\Helpers\SanitizeInput;
use Illuminate\Support\Str;
use Modules\Appointment\Entities\AppointmentCategory;
use Modules\Service\Entities\ServiceCategory;
use Plugins\PageBuilder\Fields\Number;
use Plugins\PageBuilder\Fields\Text;
use Plugins\WidgetBuilder\WidgetBase;
use function __;
use function get_user_lang;

class TenantAppointmentCategoryWithSearchWidget extends WidgetBase
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
                'label' => __('Category Title'),
                'value' => $widget_saved_values['title_' . $lang->slug] ?? null,
            ]);

            $output .= $this->admin_language_tab_content_end();
        }

        $output .= $this->admin_language_tab_end(); //have to end language tab

        $output .= Number::get([
            'name' => 'category_items',
            'label' => __('Category Items'),
            'value' => $widget_saved_values['category_items'] ?? null,
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
        $widget_title = SanitizeInput::esc_html($settings['title_' . $user_selected_language] ?? '');
        $category_items = $settings['category_items'] ?? '';
        $blog_categories = AppointmentCategory::where('status',1)->orderBy('id', 'DESC')->take($category_items)->get();
        $category_markup = '';
        $error =  view('components.error-msg')->render();



        $class_con = '';
        $form_action = '';
        foreach ($blog_categories as $item){

            $title = $item->getTranslation('title',$user_selected_language);
            $form_action = route('tenant.frontend.appointment.search');
            $url = route('tenant.frontend.appointment.category', ['id' => $item->id,'any' => Str::slug($title)]);

            $request_path = request()->path();
            $explode = explode('/',$request_path);
            $request_cat = end($explode) ?? '';
            $category_check_con = $request_cat == Str::slug($title)  ? 'checked' : '';

            $class_con = get_static_option('tenant_default_theme') == 'job-find' ?  '2' : '';

 $category_markup.= <<<LIST

        <label class="checkWrap styleTwo">
            {$title}
         <input type="checkbox" class="service_category_item_in_sidebar" {$category_check_con} data-route="{$url}">
            <span class="checkmark"></span>
        </label>

LIST;

}
$search_text = __('Search');
 return <<<HTML
           <!-- Search -->
      <div class="simplePresentCart{$class_con} mb-24">

      <h4 class="title mb-3">{$widget_title}</h4>

        <div class="searchBox-wrapper searchBox-wrapper-sidebar searchBox-wrapper-donation">
               {$error}
            <!-- Search input Box -->
               <form action="{$form_action}" method="get" class="search-form search-box">
                <div class="input-form">
                    <input type="text" class="keyup-input" name="search" placeholder="{$search_text}">
                    <!-- icon -->
                    <button class="icon" type="submit">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>
                </div>
            </form>
        </div>

        <!--Search by Topic -->
        <div class="searchByTopic">

            {$category_markup}
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
        return __('Appointment Category & Search');
    }
}
