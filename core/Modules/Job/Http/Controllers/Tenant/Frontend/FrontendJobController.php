<?php

namespace Modules\Job\Http\Controllers\Tenant\Frontend;

use App\Facades\GlobalLanguage;
use App\Traits\SeoDataConfig;
use Artesaos\SEOTools\Traits\SEOTools as SEOToolsTrait;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Job\Entities\Job;
use Modules\Job\Entities\JobCategory;
use Modules\Job\Entities\JobPaymentLog;


class FrontendJobController extends Controller
{
    use SEOToolsTrait,SeoDataConfig;
  //  private const BASE_PATH = 'job::tenant.frontend.job.';job-
    private const BASE_PATH = 'job.';

    public function job_single($slug)
    {
        $job = Job::where(['slug'=> $slug,'status'=> 1])->first();

        if (empty($job)) {
            abort(404);
        }

        $this->setMetaDataInfo($job);
        $all_related_jobs = Job::where(['status'=>1, 'category_id' => $job->category_id])->orderBy('id','desc')->take(4)->get();
        $all_categories = JobCategory::where(['status'=>1])->orderBy('id','desc')->get();

        return themeView(self::BASE_PATH.'job-single',compact(
            'job', 'all_related_jobs','all_categories'
        ));
    }

    public function category_wise_job_page($id)
    {
        if (empty($id)) {
            abort(404);
        }

        $all_job = Job::usingLocale(GlobalLanguage::default_slug())->where(['category_id' => $id,'status' => 1])->orderBy('id', 'desc')->paginate(4);
        $category = JobCategory::where(['id' => $id, 'status' => 1])->first();
        $category_name = $category->getTranslation('title',get_user_lang());

        return themeView(self::BASE_PATH.'category')->with([
            'all_job' => $all_job,
            'category_name' => $category_name,
        ]);
    }

    public function job_search_page(Request $request)
    {
        $request->validate([
            'search' => 'required'
        ],
            ['search.required' => 'Enter anything to search']);

        $all_job = Job::Where('title', 'LIKE', '%' . $request->search . '%')
            ->orderBy('id', 'desc')->paginate(4);

        return themeView(self::BASE_PATH.'search')->with([
            'all_job' => $all_job,
            'search_term' => $request->search,
        ]);
    }


    public function job_payment($slug)
    {
        $job = Job::where('slug',$slug)->firstOrFail();
        if (!empty($job->deadline) && $job->deadline <= now()) {
            return view('errors.403')->with(['message' => __('Job Expired')]);
        }

        return themeView(self::BASE_PATH.'job-payment.payment',compact('job'));

    }

    public function job_payment_success($id)
    {
        $extract_id = substr($id, 6);
        $extract_id = substr($extract_id, 0, -6);

        $check_id = !empty($extract_id) ? $extract_id : $id;
        $job_logs = JobPaymentLog::find($check_id);
        $job = Job::find($job_logs->job_id);

        return themeView(self::BASE_PATH . 'job-payment.success')->with([
            'job_logs' => $job_logs,
            'job' => $job,
        ]);
    }

    public function event_payment_cancel()
    {
        return themeView(self::BASE_PATH . 'job-payment.cancel');
    }

}
