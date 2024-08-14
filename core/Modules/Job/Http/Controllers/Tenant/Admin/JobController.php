<?php

namespace Modules\Job\Http\Controllers\Tenant\Admin;
use App\Facades\GlobalLanguage;
use App\Helpers\FlashMsg;
use App\Helpers\ResponseMessage;
use App\Mail\EventMail;
use App\Mail\JobMail;
use App\Models\Language;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Modules\Donation\Helpers\DataTableHelpers\SubAppointmentGeneral;
use Modules\Event\Actions\Event\EventAdminAction;
use Modules\Event\Entities\Event;
use Modules\Event\Entities\EventCategory;
use Modules\Event\Entities\EventComment;
use Modules\Event\Entities\EventPaymentLog;
use Modules\Event\Helpers\DataTableHelpers\EventDatatable;
use Modules\Event\Helpers\DataTableHelpers\EventGeneral;
use Modules\Event\Http\Requests\EventRequest;
use Modules\Job\Actions\Job\JobAdminAction;
use Modules\Job\Entities\Job;
use Modules\Job\Entities\JobCategory;
use Modules\Job\Entities\JobPaymentLog;
use Modules\Job\Helpers\DataTableHelpers\JobDatatable;
use Modules\Job\Helpers\DataTableHelpers\JobGeneral;
use Modules\Job\Http\Requests\JobRequest;
use Yajra\DataTables\DataTables;


class JobController extends Controller
{

    private const BASE_PATH = 'job::tenant.backend.jobs.';

    public function __construct()
    {
        $this->middleware('permission:job-list|job-create|job-edit|job-delete',['only' => 'index']);
        $this->middleware('permission:job-create',['only' => 'create','store']);
        $this->middleware('permission:job-edit',['only' => 'edit','update','clone']);
        $this->middleware('permission:job-delete',['only' => 'delete','bulk_action','job_payment_log_bulk_action']);

        /* ==== job payment log ====*/
        $this->middleware('permission:job-payment',['only' => 'job_paid_payment_logs']);
;
    }

    public function index(Request $request)
    {
        $default_lang = $request->lang ?? GlobalLanguage::default_slug();
        if ($request->ajax()){
            $data = Job::usingLocale($default_lang)->select('*')->orderBy('id','desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()

                ->addColumn('checkbox',function ($row){
                    return JobGeneral::bulkCheckbox($row->id);
                })
                ->addColumn('info',function ($row) use ($default_lang){
                    return JobDatatable::infoColumn($row,$default_lang);
                })
                ->addColumn('image',function ($row){
                    return JobGeneral::image($row->image);
                })
                ->addColumn('category',function ($row) use($default_lang){
                    return optional($row->category)->getTranslation('title',$default_lang);
                })
                ->addColumn('status',function($row){
                   return JobGeneral::statusSpan($row->status);
                })
                ->addColumn('action', function($row){
                    $action = '';
                    $route = route('tenant.frontend.job.single',$row->slug);
                    $action.= JobGeneral::viewIcon($route);
                    $admin = auth()->guard('admin')->user();

                    if ($admin->can('job-edit')){
                        $action .= JobGeneral::editIcon(route('tenant.admin.job.edit',$row->id));
                        $action .= JobGeneral::cloneIcon(route('tenant.admin.job.clone'),$row->id);
                    }
                    if ($admin->can('job-delete')){
                        $action .= JobGeneral::deletePopover(route('tenant.admin.job.delete',$row->id));
                    }

                    return $action;
                })
                ->rawColumns(['checkbox','image','info','action','status'])
                ->make(true);
        }
        return view(self::BASE_PATH . 'all-jobs',compact('default_lang'));
    }


    public function create(Request $request)
    {
        $all_category = JobCategory::select('id','title','status')->where(['status' => 1])->get();
        return view(self::BASE_PATH . 'new-job')->with([
            'all_category' => $all_category,
            'default_lang' => $request->lang ?? GlobalLanguage::default_slug(),
        ]);
    }

    public function store(JobRequest $request, JobAdminAction $action)
    {
        $current_package = tenant()->user()->first()->payment_log()->firstOrFail()->package ?? [];
        $pages_count = Job::count();
        $permission_page = $current_package->job_permission_feature;

        if(!empty($permission_page) && $pages_count >= $permission_page){
            return response()->danger(ResponseMessage::delete(sprintf('You can not create job above %d in this package',$permission_page)));
        }

        $response = $action->store_execute($request);
        return redirect()->back()->with($response);
    }

    public function edit($id)
    {
        $job = Job::findOrFail($id);
        $all_category = JobCategory::select('id','title')->get();
        return view(self::BASE_PATH . 'edit-job')->with(['job' => $job, 'all_category' => $all_category]);
    }

    public function update(JobRequest $request, $id, JobAdminAction $action)
    {
        $response = $action->update_execute($request,$id);
        return redirect()->back()->with($response);
    }

    public function delete($id)
    {
        $data = Job::findOrFail($id);
        if(!empty($data->metainfo)){
          $data->metainfo()->delete();
        }
        $data->delete();
        return redirect()->back()->with(['msg' => __('Job Deleted...'), 'type' => 'danger']);
    }

    public function clone(Request $request, JobAdminAction $action)
    {

        $current_package = tenant()->user()->first()->payment_log()->firstOrFail()->package ?? [];
        $pages_count = Job::count();
        $permission_page = $current_package->job_permission_feature;

        if(!empty($permission_page) && $pages_count >= $permission_page){
            return response()->danger(ResponseMessage::delete(sprintf('You can not create job above %d in this package',$permission_page)));
        }

        $response = $action->clone_execute($request);
        return redirect()->back()->with($response);
    }

    public function bulk_action(Request $request)
    {
        $logs = Job::find($request->ids);

        foreach ($logs as $data){
            if(!empty($data->metainfo)){
                $data->metainfo()->delete();
            }
            $data->delete();
        }
        return response()->json(['status' => 'ok']);
    }

    public function settings()
    {
        return view(self::BASE_PATH .'settings');
    }

    public function update_settings(Request $request)
    {
        foreach (Language::all() as $lang){

            $fields = $request->validate([
                'job_experience_area_'.$lang->slug.'_title' => 'nullable|string',
                'job_employee_type_area_'.$lang->slug.'_title' => 'nullable|string',
                'job_salary_offer_area_'.$lang->slug.'_title' => 'nullable|string',
                'job_working_days_area_'.$lang->slug.'_title' => 'nullable|string',
                'job_working_type_area_'.$lang->slug.'_title' => 'nullable|string',
                'job_location_area_'.$lang->slug.'_title' => 'nullable|string',
                'job_designation_area_'.$lang->slug.'_title' => 'nullable|string',
                'job_deadline_area_'.$lang->slug.'_title' => 'nullable|string',
                'job_application_fee_area_'.$lang->slug.'_title' => 'nullable|string',
                'job_apply_area_'.$lang->slug.'_title' => 'nullable|string',
            ]);

            foreach ($fields as $key => $field) {
                update_static_option($key, $request->$key);
            }
        }

        $switcher_data = [
            'job_application_fee_show_hide',
            'job_apply_show_hide',
            'job_related_area_show_hide',
        ];

        foreach ($switcher_data as $data) {
            update_static_option($data, $request->$data);
        }

        return redirect()->back()->with(FlashMsg::settings_update());
    }


    public function type(){
        return response()->json(["success" => true]);
    }


    public function job_paid_payment_logs(Request $request)
    {
        if ($request->ajax()){
            $donation_logs =  JobPaymentLog::select('*')->where('payable_status',1)->orderBy('id','desc');
            return DataTables::of($donation_logs)
                ->addIndexColumn()
                ->addColumn('checkbox',function ($row){
                    return JobGeneral::bulkCheckbox($row->id);
                })
                ->addColumn('info',function ($row){
                    return JobDatatable::paymentInfoColumn($row);
                })
                ->addColumn('status',function ($row){
                    return JobDatatable::get_job_status_with_markup($row);
                })
                ->addColumn('action', function($row){
                    $admin = auth()->guard('admin')->user();
                    $action = '';

                    if ($admin->can('event-payment-delete')){
                        $action .= JobGeneral::deletePopover(route('tenant.admin.job.payment.log.delete',$row->id));
                    }

                    if ($admin->can('event-payment-edit')){
                        if($row->payment_gateway == 'manual_payment_' && !empty($row->manual_payment_attachment)){
                            $action .= JobGeneral::viewAttachment($row);
                        }
                        if($row->status == 1){
                            $action .= JobGeneral::invoiceBtn(route('tenant.admin.job.invoice.generate'),$row->id);
                        }

                        $action .= JobGeneral::viewResumeAttachment($row);


                        if($row->status == 0){
                            $action .= JobGeneral::paymentAccept(route('tenant.admin.job.payment.accept',$row->id));
                        }
                    }

                    return $action;
                })
                ->rawColumns(['action','checkbox','info','status'])
                ->make(true);
        }
        return view(self::BASE_PATH.'payment-data.paid-payment-logs');
    }

    public function job_unpaid_payment_logs(Request $request)
    {
        if ($request->ajax()){
            $donation_logs =  JobPaymentLog::select('*')->where('payable_status',0)->orderBy('id','desc');
            return DataTables::of($donation_logs)
                ->addIndexColumn()
                ->addColumn('checkbox',function ($row){
                    return JobGeneral::bulkCheckbox($row->id);
                })
                ->addColumn('info',function ($row){
                    return JobDatatable::paymentInfoColumn($row);
                })
                ->addColumn('status',function ($row){
                    return JobDatatable::get_job_status_with_markup($row);
                })
                ->addColumn('action', function($row){
                    $admin = auth()->guard('admin')->user();
                    $action = '';

                    if ($admin->can('event-payment-delete')){
                        $action .= JobGeneral::deletePopover(route('tenant.admin.job.payment.log.delete',$row->id));
                    }

                    if ($admin->can('event-payment-edit')){
                        if($row->payment_gateway == 'manual_payment_' && !empty($row->manual_payment_attachment)){
                            $action .= JobGeneral::viewAttachment($row);
                        }

                        $action .= JobGeneral::viewResumeAttachment($row);
                    }

                    return $action;
                })
                ->rawColumns(['action','checkbox','info','status'])
                ->make(true);
        }
        return view(self::BASE_PATH.'payment-data.unpaid-logs');
    }

    public function job_payment_logs_report(Request $request)
    {
        $order_data = '';
        $query = JobPaymentLog::where('payable_status',1);
        if (!empty($request->start_date)) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if (!empty($request->end_date)) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        if (!empty($request->status)) {
            $query->where(['status' => $request->status]);
        }
        $error_msg = __('select start & end date to generate event payment report');
        if (!empty($request->start_date) && !empty($request->end_date)) {
            $query->orderBy('id', 'DESC');
            $order_data = $query->paginate($request->items);
            $error_msg = '';
        }

        return view(self::BASE_PATH .'payment-data.job-report')->with([
            'order_data' => $order_data,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'items' => $request->items,
            'payment_status' => $request->status,
            'error_msg' => $error_msg
        ]);
    }

    public function job_payment_log_delete($id)
    {
        JobPaymentLog::findOrFail($id)->delete();
        return redirect()->back()->with(['msg' => __('Event Payment Log Deleted..'), 'type' => 'danger']);
    }

    public function job_payment_log_bulk_action(Request $request)
    {
        JobPaymentLog::whereIn('id',$request->ids)->delete();
        return response()->json(['status' => 'ok']);
    }

    public function job_invoice(Request $request)
    {
        $job_details = JobPaymentLog::find($request->id);
        if (empty($job_details)) {
            return abort(404);
        }

        $pdf = PDF::loadview('tenant.frontend.invoice.job', ['job_details' => $job_details])
            ->setOptions(['defaultFont' => 'sans-serif','isRemoteEnabled'=>true]);
        return $pdf->download('job-invoice.pdf');
    }

    public function job_payment_accept($id)
    {
        $payment_details = JobPaymentLog::findOrFail($id);
        $payment_details->status = 1;
        $payment_details->save();

        $customer_subject = __('Your job payment approved in').' '.get_static_option('site_'.get_user_lang().'_title');
        $admin_subject = __('Payment Approved successfully..!').' '.get_static_option('site_'.get_user_lang().'_title');

        try {
            Mail::to(get_static_option('tenant_site_global_email'))->send(new JobMail($payment_details, $admin_subject,"admin"));
            Mail::to($payment_details->email)->send(new JobMail( $payment_details, $customer_subject,'user'));

        } catch (\Exception $e) {
            return redirect()->back()->with(['type' => 'danger', 'msg' => $e->getMessage()]);
        }

        return redirect()->back()->with(['msg' => __('Payment Accepted Successfully..!'), 'type' => 'success']);
    }

}
