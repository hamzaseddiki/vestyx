<?php

namespace Plugins\PageBuilder\Addons\Tenants\Event;
use App\Helpers\SanitizeInput;
use Modules\Event\Entities\Event;
use Modules\Event\Entities\EventCategory;
use Plugins\PageBuilder\Fields\Number;
use Plugins\PageBuilder\PageBuilderBase;

class EventWithFilter extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Tenant/Event/event-with-filter.png';
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
        //For filter event
        if(!is_null($filter_search) || !is_null($filter_sort) || !is_null($filter_category_id)){

            $event = Event::where('status',1);

            if(!is_null($filter_search)){
                $event = $event->Where('title', 'LIKE', '%' .$filter_search . '%')->orderBy('id', 'desc')->paginate(4);
                $search_term = $filter_search;

            }elseif (!is_null($filter_sort)){
                $order = '';
                if($filter_sort == 'recent' || $filter_sort == 'new'){
                    $order = 'desc';
                }else{
                    $order = 'asc';
                }
                $event = $event->orderBy('id', $order)->paginate(6);
                $search_term = $filter_search;

            }elseif (!is_null($filter_category_id)){
                $event = $event->where('category_id',$filter_category_id)->orderBy('id', 'desc')->paginate(6);
                $cat =  EventCategory::find($filter_category_id);
                $search_term = $cat->getTranslation('title',get_user_lang());
            }

        }else{


            $event = Event::where('status', 1);
            if(!empty($items)){
                $event = $event->paginate($items);
            }else{
                $event = $event->paginate(4);
            }

        }

        $all_categories = EventCategory::select('id','title')->get();

        $data = [
            'event'=> $event,
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
            'all_categories'=> $all_categories,
            'search_term'=> $search_term,
        ];

        return self::renderView('tenant.event.event-with-filter',$data);

    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Event With Filter');
    }
}
