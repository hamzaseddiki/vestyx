<?php

namespace Plugins\PageBuilder\Addons\Landlord\Common;

use App\Facades\GlobalLanguage;
use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;

use Plugins\PageBuilder\Fields\IconPicker;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\Number;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\Slider;
use Plugins\PageBuilder\Fields\Switcher;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\PageBuilder\PageBuilderBase;

class PricePlan extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Landlord/common/price-plan-01.png';
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
                'name' => 'trial_text_'.$lang->slug,
                'label' => __('Trial Text'),
                'value' => $widget_saved_values['trial_text_'.$lang->slug] ?? null,
            ]);
            $output .= $this->admin_language_tab_content_end();
        }
        $output .= $this->admin_language_tab_end(); //have to end language tab



        $output .= Select::get([
            'name' => 'order_by',
            'label' => __('Order By'),
            'options' => [
                'id' => __('ID'),
                'created_at' => __('Date'),
                'price' => __('Price'),
            ],
            'value' => $widget_saved_values['order_by'] ?? null,
            'info' => __('set order by')
        ]);
        $output .= Select::get([
            'name' => 'order',
            'label' => __('Order'),
            'options' => [
                'asc' => __('Ascending'),
                'desc' => __('Descending'),
            ],
            'value' => $widget_saved_values['order'] ?? null,
            'info' => __('set order')
        ]);


        $output .= Text::get([
            'name' => 'item_show',
            'label' => __('Item Show'),
            'value' => $widget_saved_values['item_show'] ?? null,
            'info' => __('set how many item you want to show on each group (by default if you have not selected it will show all items)')
        ]);

        $output .= Switcher::get([
            'name' => 'plan_type_status',
            'label' => __('Show/Hide Plan Type'),
            'value' => $widget_saved_values['plan_type_status'] ?? null,
            'info' => __('You can show /hide plan type by this.)')
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
        $item = SanitizeInput::esc_html($this->setting_item('item_show'));

        $current_lang = GlobalLanguage::user_lang_slug();
        $title = $this->setting_item('title_'.$current_lang) ?? '';
        $button_text = $this->setting_item('button_text_'.$current_lang) ?? '';
        $trial_text = $this->setting_item('trial_text_'.$current_lang) ?? '';

        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));

        $order_by = SanitizeInput::esc_html($this->setting_item('order_by'));
        $order = SanitizeInput::esc_html($this->setting_item('order'));
        $plan_type_status = $this->setting_item('plan_type_status');

        $all_price_plan = \App\Models\PricePlan::with('plan_features')
            ->where('status',1)
            ->whereNot('type',3)
            ->orderBy($order_by,$order)->get()
            ->groupBy('type')->sortByDesc('id');

        $data = [
            'title'=> $title,
            'button_text'=> $button_text,
            'trial_text'=> $trial_text,
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
            'all_price_plan'=> $all_price_plan,
            'item'=> $item,
            'plan_type_status'=> $plan_type_status,
        ];

        return self::renderView('landlord.addons.common.price-plan',$data);

    }

    public function enable(): bool
    {
        return (bool) is_null(tenant());
    }

    public function addon_title()
    {
        return __('Price Plan : 01');
    }
}
