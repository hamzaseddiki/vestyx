<?php

namespace Plugins\PageBuilder\Addons\Tenants\Donation;

use App\Facades\GlobalLanguage;
use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use App\Models\Testimonial;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\Number;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\PageBuilderBase;
use function __;

class DonationTestimonial extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Tenant/donation/donation-testimonial-01.png';
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
                'info' => __('To show the highlighted text, place your word between this code {h}YourText{/h}')
            ]);

            $output .= $this->admin_language_tab_content_end();
        }
        $output .= $this->admin_language_tab_end(); //have to end language tab


        $output .= Number::get([
            'name' => 'item',
            'label' => __('Item'),
            'value' => $widget_saved_values['item'] ?? null,
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

        $output .= Image::get([
            'name' => 'right_image',
            'label' => __('Right Image'),
            'value' => $widget_saved_values['right_image'] ?? null,
            'dimensions'=> __('(636*442)')
        ]);

        $output .= Text::get([
            'name' => 'right_video_url',
            'label' => __('Right Video URL'),
            'value' => $widget_saved_values['right_video_url'] ?? null,
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
        $current_lang = get_user_lang();
        $title = $this->setting_item('title_'.$current_lang) ?? '';
        $item = $this->setting_item('item') ?? '';
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));

        $order_by = SanitizeInput::esc_html($this->setting_item('order_by'));
        $order = SanitizeInput::esc_html($this->setting_item('order'));


        $right_image = $this->setting_item('right_image') ?? null;
        $right_video_url = $this->setting_item('right_video_url') ?? null;

        $testimonial = Testimonial::query();
        $testimonial = $testimonial->where('status',1)->orderBy($order_by,$order);

        if(!empty($item)) {
            if($item < 2){
                $testimonial = $testimonial->take(2)->get();
            }else{
                $testimonial = $testimonial->take($item)->get();
            }
        }else{
             $testimonial = $testimonial->take(3)->get();
        }

        $data = [
            'title'=> $title,
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
            'testimonial'=> $testimonial,
            'right_image'=> $right_image,
            'right_video_url'=> $right_video_url,
        ];

        return self::renderView('tenant.donation.donation-testimonial',$data);

    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Donation Testimonial');
    }
}
