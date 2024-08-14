<?php

namespace Plugins\PageBuilder\Addons\Tenants\Common\misc;

use App\Helpers\LanguageHelper;
use App\Helpers\SanitizeInput;
use App\Models\Testimonial;
use Modules\Service\Entities\Service;
use Plugins\PageBuilder\Fields\IconPicker;
use Plugins\PageBuilder\Fields\Image;
use Plugins\PageBuilder\Fields\NiceSelect;
use Plugins\PageBuilder\Fields\Number;
use Plugins\PageBuilder\Fields\Select;
use Plugins\PageBuilder\Fields\Text;
use Plugins\PageBuilder\PageBuilderBase;
use function __;

class ServiceOne extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Tenant/Common/service-01.png';
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
        $item = $this->setting_item('item') ?? '';
        $padding_top = SanitizeInput::esc_html($this->setting_item('padding_top'));
        $padding_bottom = SanitizeInput::esc_html($this->setting_item('padding_bottom'));
        $order_by = SanitizeInput::esc_html($this->setting_item('order_by'));
        $order = SanitizeInput::esc_html($this->setting_item('order'));

        $service = Service::query();
        $service = $service->where('status',1)->orderBy($order_by ,$order);
        if(!empty($item)) {

            if($item < 2){
                $service = $service->paginate(2);
            }else{
                $service = $service->paginate($item);
            }

        }else{
             $service = $service->paginate(3);
        }

        $data = [
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
            'service'=> $service,
        ];

        return self::renderView('tenant.Common.misc.service-one',$data);

    }

    public function enable(): bool
    {
        return (bool) !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Service : 01');
    }
}
