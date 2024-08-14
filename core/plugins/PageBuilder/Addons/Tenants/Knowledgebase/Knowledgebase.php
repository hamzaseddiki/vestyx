<?php

namespace Plugins\PageBuilder\Addons\Tenants\Knowledgebase;

use App\Facades\GlobalLanguage;
use App\Helpers\SanitizeInput;

use Modules\Blog\Entities\Blog;
use Modules\Blog\Entities\BlogCategory;
use Modules\Event\Entities\Event;
use Modules\Event\Entities\EventCategory;
use Modules\Job\Entities\Job;
use Modules\Knowledgebase\Entities\KnowledgebaseCategory;
use Plugins\PageBuilder\Fields\Number;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\PageBuilderBase;

class Knowledgebase extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Tenant/Knowledgebase/knowledgebase-01.png';
    }

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
                'value' => $widget_saved_values['title_'.$lang->slug] ?? null,
                'info' => __('To show the highlighted text, place your word between this code {h}YourText{/h]')
            ]);
            $output .= Text::get([
                'name' => 'button_text_'.$lang->slug,
                'label' => __('Button Text'),
                'value' => $widget_saved_values['button_text_'.$lang->slug] ?? null,
            ]);
            $output .= Text::get([
                'name' => 'button_url_'.$lang->slug,
                'label' => __('Button URL'),
                'value' => $widget_saved_values['button_url_'.$lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'explore_text_'.$lang->slug,
                'label' => __('Explore Text'),
                'value' => $widget_saved_values['explore_text_'.$lang->slug] ?? null,
            ]);
            $output .= Text::get([
                'name' => 'explore_url_'.$lang->slug,
                'label' => __('Explore URL'),
                'value' => $widget_saved_values['explore_url_'.$lang->slug] ?? null,
            ]);
            $output .= $this->admin_language_tab_content_end();
        }
        $output .= $this->admin_language_tab_end(); //have to end language tab


        $categories = KnowledgebaseCategory::usingLocale(GlobalLanguage::default_slug())->where(['status' => 1])->get()->mapWithKeys(function ($item){
            return [$item->id => $item->getTranslation('title',GlobalLanguage::default_slug())];
        })->toArray();

        $output .= Number::get([
            'name' => 'items',
            'label' => __('Category Items'),
            'value' => $widget_saved_values['items'] ?? null,
            'info' => __('enter how many item you want to show in frontend'),
        ]);

        $output .= Number::get([
            'name' => 'article_items',
            'label' => __('Articel Items'),
            'value' => $widget_saved_values['article_items'] ?? null,
            'info' => __('enter how many item you want to show in frontend'),
        ]);


        // add padding option
        $output .= $this->padding_fields($widget_saved_values);
        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        $current_lang = GlobalLanguage::user_lang_slug();
        $items = SanitizeInput::esc_html($this->setting_item('items'));
        $article_items = SanitizeInput::esc_html($this->setting_item('article_items') ?? 2);

        $title = $this->setting_item('title_'.$current_lang);
        $button_text = $this->setting_item('button_text_'.$current_lang);
        $button_url = $this->setting_item('button_url_'.$current_lang);
        $explore_text = $this->setting_item('explore_text_'.$current_lang);
        $explore_url = $this->setting_item('explore_url_'.$current_lang);

        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));


            if(!empty($items)){
                $all_knowledgebase = KnowledgebaseCategory::with(["knowledgebase"])->where(['status' => 1])->take($items)->get()->map(function($query) use ($article_items) {
                    $query->setRelation('knowledgebase', $query->knowledgebase->take($article_items));
                    return $query;
                });
            }else{
                $all_knowledgebase = KnowledgebaseCategory::with(["knowledgebase"])->where(['status' => 1])->get()->map(function($query)  use ($article_items){
                    $query->setRelation('knowledgebase', $query->knowledgebase->take($article_items));
                    return $query;
                });
            }

        $data = [
            'title'=> $title,
            'all_knowledgebase'=> $all_knowledgebase,
            'button_text'=> $button_text,
            'button_url'=> $button_url,
            'explore_text'=> $explore_text,
            'explore_url'=> $explore_url,
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
        ];

        return self::renderView('tenant.knowledgebase.knowledgebase-one',$data);
    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Knowledgebase : 01');
    }
}
