<?php

namespace Plugins\PageBuilder\Addons\Tenants\Donation;

use App\Facades\GlobalLanguage;
use App\Helpers\SanitizeInput;
use Modules\Donation\Entities\DonationActivity;
use Plugins\PageBuilder\Fields\NiceSelect;
use Plugins\PageBuilder\Fields\Number;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\PageBuilderBase;

class DonationActivities extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Tenant/donation/donation-activities-01.png';
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

            $output .= $this->admin_language_tab_content_end();
        }
        $output .= $this->admin_language_tab_end(); //have to end language tab


        $acti = DonationActivity::usingLocale(GlobalLanguage::default_slug())->where(['status' => 1])->get()->mapWithKeys(function ($item){
            return [$item->id => $item->getTranslation('title',GlobalLanguage::default_slug())];
        })->toArray();

        $output .= NiceSelect::get([
            'multiple' => true,
            'name' => 'acti',
            'label' => __('Select Category'),
            'placeholder' => __('Select Category'),
            'options' => $acti,
            'value' => $widget_saved_values['acti'] ?? null,
            'info' => __('you can select your desired blog categories or leave it empty')
        ]);

        $output .= Select::get([
            'name' => 'order_by',
            'label' => __('Order By'),
            'options' => [
                'id' => __('ID'),
                'created_at' => __('Date'),
            ],
            'value' => $widget_saved_values['order_by'] ?? null,
            'info' => __('set order by')
        ]);


        $output .= Select::get([
            'name' => 'order',
            'label' => __('Order'),
            'options' => [
                'asc' => __('Accessing'),
                'desc' => __('Decreasing'),
            ],
            'value' => $widget_saved_values['order'] ?? null,
            'info' => __('set order')
        ]);
        $output .= Number::get([
            'name' => 'items',
            'label' => __('Items'),
            'value' => $widget_saved_values['items'] ?? null,
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
        $category = $this->setting_item('acti') == 'Select Category' ? '' : $this->setting_item('acti') ;
        $order_by = SanitizeInput::esc_html($this->setting_item('order_by'));
        $order = SanitizeInput::esc_html($this->setting_item('order'));
        $items = SanitizeInput::esc_html($this->setting_item('items'));
        $title = $this->setting_item('title_'.$current_lang);

        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
        $donors_count_type = SanitizeInput::esc_html($this->setting_item('donors_count_type'));

        $activities = DonationActivity::query();


        if(!empty($category)) {

            if (!empty($items)) {
                $activities =  $activities->where('status', 1)->whereIn('category_id',$category)->orderBy($order_by,$order)->take($items)->get();
            } else {
                $activities =  $activities->where('status', 1)->whereIn('category_id',$category)->orderBy($order_by,$order)->take(4)->get();
            }
        }else{
            $activities = $activities->where('status', 1)->orderBy($order_by,$order)->take(4)->get();
        }


        $data = [
            'title'=> $title,
            'activities'=> $activities,
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
            'donors_count_type'=> $donors_count_type,
        ];

        return self::renderView('tenant.donation.donation-activities',$data);

    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Donation Activities');
    }
}
