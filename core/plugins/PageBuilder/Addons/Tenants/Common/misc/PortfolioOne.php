<?php

namespace Plugins\PageBuilder\Addons\Tenants\Common\misc;

use App\Helpers\SanitizeInput;
use Modules\Portfolio\Entities\Portfolio;
use Plugins\PageBuilder\Fields\Number;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\PageBuilderBase;

class PortfolioOne extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Tenant/common/portfolio-01.png';
    }

    public function admin_render()
    {
        $output = $this->admin_form_before();
        $output .= $this->admin_form_start();
        $output .= $this->default_fields();
        $widget_saved_values = $this->get_settings();

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



        // add padding option
        $output .= $this->padding_fields($widget_saved_values);
        $output .= $this->admin_form_submit_button();
        $output .= $this->admin_form_end();
        $output .= $this->admin_form_after();

        return $output;
    }

    public function frontend_render()
    {
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));

        $item = $this->setting_item('item');
        $order_by = SanitizeInput::esc_html($this->setting_item('order_by'));
        $order = SanitizeInput::esc_html($this->setting_item('order'));

        $portfolio = Portfolio::query();
        if(!empty($item)){
            $portfolio = $portfolio->where('status',1)->orderBy($order_by,$order)->paginate($item);
        }else{
            $portfolio = $portfolio->where('status',1)->orderBy($order_by,$order )->paginate(4);
        }

        $data = [
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
            'portfolio' => $portfolio,
        ];

        return self::renderView('tenant.Common.misc.portfolio-one',$data);
    }

    public function enable(): bool
    {
        return (bool) !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Portfolio: 01');
    }
}
