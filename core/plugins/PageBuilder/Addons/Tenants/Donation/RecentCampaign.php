<?php

namespace Plugins\PageBuilder\Addons\Tenants\Donation;

use App\Facades\GlobalLanguage;
use App\Helpers\SanitizeInput;
use Modules\Donation\Entities\Donation;
use Modules\Donation\Entities\DonationCategory;
use Plugins\PageBuilder\Fields\Number;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\PageBuilderBase;

class RecentCampaign extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Tenant/donation/recent-campaign.png';
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


        $categories = DonationCategory::usingLocale(GlobalLanguage::default_slug())->where(['status' => 1])->get()->mapWithKeys(function ($item){
            return [$item->id => $item->getTranslation('title',GlobalLanguage::default_slug())];
        })->toArray();

        $output .= Select::get([
            'name' => 'categories',
            'label' => __('Select Category'),
            'placeholder' => __('Select Category'),
            'options' => $categories,
            'value' => $widget_saved_values['categories'] ?? null,
            'info' => __('you can select your desired blog categories or leave it empty')
        ]);

        $output .= Select::get([
            'name' => 'order_by',
            'label' => __('Order By'),
            'options' => [
                'id' => __('ID'),
                'created_at' => __('Date'),
                'views' => __('Popular/Views'),
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


        $output .= Select::get([
            'name' => 'donors_count_type',
            'label' => __('Donor Count Type'),
            'options' => [
                'with_image' => __('With Image'),
                'without_image' => __('Without Image'),
            ],
            'value' => $widget_saved_values['donors_count_type'] ?? null,
            'info' => __('You can set deffrent bottom donor type by this')
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
        $category = $this->setting_item('categories') == 'Select Category' ? '' : $this->setting_item('categories') ;
        $order_by = SanitizeInput::esc_html($this->setting_item('order_by'));
        $order = SanitizeInput::esc_html($this->setting_item('order'));
        $items = SanitizeInput::esc_html($this->setting_item('items'));
        $title = $this->setting_item('title_'.$current_lang);

        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
        $donors_count_type = SanitizeInput::esc_html($this->setting_item('donors_count_type'));

        $donation = Donation::where('status', 1);


        if(!empty($category)) {

            if (!empty($items)) {
                $donation =  $donation->where('category_id',$category)->whereDate('deadline', '>',now())->orderBy($order_by,$order)->take($items)->get();
            } else {
                $donation =  $donation->where('category_id',$category)->whereDate('deadline', '>',now())->orderBy($order_by,$order)->take(5)->get();
            }
        }else{
            $donation = $donation->where('status', 1)->whereDate('deadline', '>',now())->orderBy($order_by,$order)->take(6)->get();
        }

        $data = [
            'title'=> $title,

            'donation'=> $donation,
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
            'donors_count_type'=> $donors_count_type,
        ];

        return self::renderView('tenant.donation.recent-campaign',$data);

    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Donation Campaigns');
    }
}
