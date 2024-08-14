<?php

namespace Plugins\PageBuilder\Addons\Tenants\Donation;

use App\Helpers\SanitizeInput;
use Modules\Donation\Entities\Donation;
use Modules\Donation\Entities\DonationCategory;
use Plugins\PageBuilder\Fields\Number;
use Plugins\PageBuilder\PageBuilderBase;

class DonationWithFilter extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Tenant/donation/donation-with-filter.png';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();

        $widget_saved_values = $this->get_settings();

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

        $items = SanitizeInput::esc_html($this->setting_item('items'));
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));

        $filter_search = request()->get('filter_search');
        $filter_sort = request()->get('filter_sort');
        $filter_category_id = request()->get('filter_category_id');

        $search_term = '';
        //For filter donation
        if(!is_null($filter_search) ||  !is_null($filter_sort) || !is_null($filter_category_id)){

           $donation = Donation::where('status',1);

            if(!is_null($filter_search)){
                $donation = $donation->Where('title', 'LIKE', '%' .$filter_search . '%')->orderBy('id', 'desc')->paginate(4);
                $search_term = $filter_search;

            }elseif (!is_null($filter_sort)){
                $order = '';
                if($filter_sort == 'recent' || $filter_sort == 'new'){
                    $order = 'desc';
                }else{
                    $order = 'asc';
                }
                $donation = $donation->orderBy('id', $order)->paginate(6);
                $search_term = $filter_search;

            }elseif (!is_null($filter_category_id)){
                $donation = $donation->where('category_id',$filter_category_id)->orderBy('id', 'desc')->paginate(6);
                $cat =  DonationCategory::find($filter_category_id);
                $search_term = $cat->getTranslation('title',get_user_lang());
            }

        }else{

            $donation = Donation::where('status', 1);
            if(!empty($items)){
                $donation = $donation->whereDate('deadline', '>',now())->paginate($items);
            }else{
                $donation = $donation->whereDate('deadline', '>',now())->paginate(4);
            }
        }

        $all_categories = DonationCategory::select('id','title')->get();

        $data = [
            'donation'=> $donation,
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
            'all_categories'=> $all_categories,
            'search_term'=> $search_term,
        ];

        return self::renderView('tenant.donation.donation-with-filter',$data);

    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Donation With Filter');
    }
}
