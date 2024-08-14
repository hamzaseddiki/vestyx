<?php

namespace Plugins\PageBuilder\Addons\Tenants\Job;
use App\Helpers\SanitizeInput;
use Modules\Event\Entities\Event;
use Modules\Event\Entities\EventCategory;
use Modules\Job\Entities\Job;
use Plugins\PageBuilder\Fields\Number;
use Plugins\PageBuilder\PageBuilderBase;

class JobWithFilter extends PageBuilderBase
{

    public function preview_image()
    {
        return 'Tenant/Job/job-with-filter.png';
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

        $filter_search_title = request()->get('filter_search_title');
        $filter_search_location = request()->get('filter_search_location');
        $filter_sort_salary = request()->get('filter_sort_salary');
        $filter_category_id = request()->get('filter_category_id');

        $search_term = '';
        //For filter job
        if(!is_null($filter_search_title) || !is_null($filter_search_location) || !is_null($filter_sort_salary) || !is_null($filter_category_id)){

            $job = Job::where('status',1);

            if(!is_null($filter_search_title) || !is_null($filter_search_location)){
                if(is_null($filter_search_location)) {
                    $job = $job->Where('title', 'LIKE', '%' . $filter_search_title . '%')
                        ->orderBy('id', 'desc')->paginate(4);

                }else if(!is_null($filter_search_location)){
                    $job = $job->Where('job_location', 'LIKE', '%' .$filter_search_location . '%')
                        ->orderBy('id', 'desc')->paginate(4);
                }else{
                    $job = $job->Where('title', 'LIKE', '%' .$filter_search_title . '%')
                               ->Where('job_location', 'LIKE', '%' .$filter_search_location .'%')
                               ->orderBy('id', 'desc')->paginate(4);
                }

                $search_term = purify_html($filter_search_title);

            }elseif (!is_null($filter_sort_salary)){
                $after_explode = [];

                 switch ($filter_sort_salary){
                     case '10000-20000':
                     case '20000-30000':
                     case '30000-40000':
                     case '40000-50000':
                     case '50000-70000':
                     case '70000-100000':

                         $get_range = explode('-',$filter_sort_salary) ?? [];
                         $after_explode = $get_range;
                         $first = current($get_range);
                         $last = end($get_range);
                         $job->whereBetween('salary_offer', [$first,$last]);
                     break;
                     default;
                 }
                $job = $job->paginate(6);

                $search_term = purify_html(current($after_explode) .site_currency_symbol() .' to '. end($after_explode)) . site_currency_symbol();

            }elseif (!is_null($filter_category_id)){
                $job = $job->where('category_id',$filter_category_id)->orderBy('id', 'desc')->paginate(6);
                $cat =  \Modules\Job\Entities\JobCategory::find($filter_category_id);
                $search_term = purify_html($cat->getTranslation('title',get_user_lang()));
            }

        }else{

            $job = Job::where('status', 1);
            if(!empty($items)){
                $job = $job->paginate($items);
            }else{
                $job = $job->paginate(4);
            }

        }

        $all_categories = \Modules\Job\Entities\JobCategory::select('id','title')->get();

        $data = [
            'job'=> $job,
            'padding_top'=> $padding_top,
            'padding_bottom'=> $padding_bottom,
            'all_categories'=> $all_categories,
            'search_term'=> $search_term,
        ];

        return self::renderView('tenant.job.job-with-filter',$data);

    }

    public function enable(): bool
    {
        return !is_null(tenant());
    }

    public function addon_title()
    {
        return __('Job With Filter');
    }
}
