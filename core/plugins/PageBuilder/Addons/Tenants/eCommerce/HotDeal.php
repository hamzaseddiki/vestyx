<?php

namespace Plugins\PageBuilder\Addons\Tenants\eCommerce;
use App\Facades\GlobalLanguage;
use App\Helpers\SanitizeInput;
use App\Models\FormBuilder;
use Modules\Campaign\Entities\Campaign;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\Repeater;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\Slider;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\Fields\Textarea;
use Plugins\PageBuilder\Helpers\RepeaterField;
use Plugins\PageBuilder\PageBuilderBase;
use function __;

class HotDeal extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Tenant/eCommerce/hot-deal.png';
    }

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
                'name' => 'title_'.$lang->slug,
                'label' => __('Title'),
                'value' => $widget_saved_values['title_'.$lang->slug] ?? null,
            ]);

            $output .= Text::get([
                'name' => 'button_text_'.$lang->slug,
                'label' => __('Button Text'),
                'value' => $widget_saved_values['button_text_'.$lang->slug] ?? null,
            ]);

            $output .= $this->admin_language_tab_content_end();
        }
        $output .= $this->admin_language_tab_end(); //have to end language tab

        $campaigns = Campaign::select('id','title')->where(['status' => 'publish'])->get()->pluck('title','id');

        $output .= Select::get([
            'name' => 'campaign_id',
            'label' => __('Select Campaign'),
            'placeholder' => __('Select Campaign'),
            'options' => $campaigns,
            'value' => $widget_saved_values['campaign_id'] ?? null,
            'info' => __('you can select your desired products or leave it empty')
        ]);

        $output .= Image::get([
            'name' => 'offer_image',
            'label' => __('Offer Image'),
            'value' => $widget_saved_values['offer_image'] ?? null,
        ]);

        $output .= Image::get([
            'name' => 'right_image',
            'label' => __('Right Image'),
            'value' => $widget_saved_values['right_image'] ?? null,
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
        $lang = get_user_lang();
        $title = SanitizeInput::esc_html($this->setting_item('title_'.$lang) ?? '');
        $button_text = SanitizeInput::esc_html($this->setting_item('button_text_'.$lang) ?? '');
        $right_image = SanitizeInput::esc_html($this->setting_item('right_image') ?? '');
        $offer_image = SanitizeInput::esc_html($this->setting_item('offer_image') ?? '');
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
        $repeater_data = $this->setting_item('eCommerce_best_deal_repeater');

        $campaign = Campaign::with('products','products.product')->first();

        $data = [
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
            'title'=> $title,
            'button_text'=> $button_text,
            'right_image'=> $right_image,
            'offer_image'=> $offer_image,
            'campaign'=> $campaign,
        ];

        return self::renderView('tenant.eCommerce.hot-deal',$data);
    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Hot Deal (eCommerce)');
    }
}
