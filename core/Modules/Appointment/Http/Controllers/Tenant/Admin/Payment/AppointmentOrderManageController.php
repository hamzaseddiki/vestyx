<?php

namespace Modules\Appointment\Http\Controllers\Tenant\Admin\Payment;

use App\Facades\GlobalLanguage;
use App\Helpers\DataTableHelpers\General;
use App\Helpers\FlashMsg;
use App\Helpers\ResponseMessage;
use App\Mail\AppointmentMail;
use App\Mail\JobMail;
use App\Models\Language;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Modules\Appointment\Actions\Appointment\AppointmentAdminAction;
use Modules\Appointment\Entities\Appointment;
use Modules\Appointment\Entities\AppointmentCategory;
use Modules\Appointment\Entities\AppointmentComment;
use Modules\Appointment\Entities\AppointmentPaymentLog;
use Modules\Appointment\Entities\AppointmentSubcategory;
use Modules\Appointment\Entities\AppointmentTax;
use Modules\Appointment\Entities\SubAppointment;
use Modules\Appointment\Helpers\DataTableHelpers\AppointmentDatatable;
use Modules\Appointment\Helpers\DataTableHelpers\AppointmentGeneral;
use Modules\Appointment\Http\Requests\AppointmentRequest;
use Modules\Job\Entities\JobPaymentLog;
use Modules\Job\Helpers\DataTableHelpers\JobDatatable;
use Modules\Job\Helpers\DataTableHelpers\JobGeneral;
use Yajra\DataTables\DataTables;

class AppointmentOrderManageController extends Controller
{
    private const BASE_PATH = 'appointment::tenant.backend.appointment-section.appointment.payment-data.';

    public function appointment_complete_payment_logs(Request $request)
    {
        if ($request->ajax()){
            $donation_logs =  AppointmentPaymentLog::select('*')->orderBy('id','desc');
            return DataTables::of($donation_logs)
                ->addIndexColumn()
                ->addColumn('checkbox',function ($row){
                    return AppointmentGeneral::bulkCheckbox($row->id);
                })
                ->addColumn('info',function ($row){
                    return AppointmentDatatable::paymentInfoColumn($row);
                })
                ->addColumn('additional_info',function ($row){
                    return AppointmentDatatable::paymentAdditionalInfoColumn($row->sub_appointment_log_items);
                })
                ->addColumn('status',function ($row){
                    return AppointmentDatatable::get_status_with_markup($row);
                })
                ->addColumn('action', function($row){
                    $admin = auth()->guard('admin')->user();
                    $action = '';

                    if ($admin->can('appointment-payment-delete')){
                        $action .= AppointmentGeneral::deletePopover(route('tenant.admin.appointment.payment.log.delete',$row->id));
                    }

                    if ($admin->can('appointment-payment-edit')){
                        if($row->payment_gateway == 'manual_payment_' && !empty($row->manual_payment_attachment)){
                            $action .= AppointmentGeneral::viewAttachment($row);
                        }
                        if($row->payment_status == 'complete'){
                            $action .= AppointmentGeneral::invoiceBtn(route('tenant.admin.appointment.invoice.generate'),$row->id);
                        }

                        if($row->payment_status != 'complete'){
                            $action .= AppointmentGeneral::paymentAccept(route('tenant.admin.appointment.payment.accept',$row->id));
                        }
                    }

                    return $action;
                })
                ->rawColumns(['action','checkbox','info','status','additional_info'])
                ->make(true);
        }
        return view(self::BASE_PATH.'.payment-logs');
    }


    public function appointment_payment_logs_report(Request $request)
    {
        $order_data = '';
        $query = AppointmentPaymentLog::query();
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

        return view(self::BASE_PATH .'appointment-report')->with([
            'order_data' => $order_data,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'items' => $request->items,
            'payment_status' => $request->status,
            'error_msg' => $error_msg
        ]);
    }

    public function appointment_payment_log_delete($id)
    {
        $log =  AppointmentPaymentLog::with('sub_appointment_log_items')->findOrFail($id);
        $log->additional_appointment_logs()?->delete();
        return redirect()->back()->with(['msg' => __('Appointment Payment Log Deleted..'), 'type' => 'danger']);
    }

    public function appointment_payment_log_bulk_action(Request $request)
    {
        $logs = AppointmentPaymentLog::whereIn('id',$request->ids)->get();

        foreach ($logs as $log){
            $log->additional_appointment_logs()?->delete();
            $log->delete();
        }

        return response()->json(['status' => 'ok']);
    }

    public function appointment_invoice(Request $request)
    {
        $appointment_details = AppointmentPaymentLog::find($request->id);
        if (empty($appointment_details)) {
            return abort(404);
        }

        $pdf = PDF::loadview('tenant.frontend.invoice.appointment', ['appointment_details' => $appointment_details])
            ->setOptions(['defaultFont' => 'sans-serif','isRemoteEnabled'=>true]);
        return $pdf->download('appointment-invoice.pdf');
    }

    public function appointment_payment_accept($id)
    {
        $payment_details = AppointmentPaymentLog::findOrFail($id);
        $payment_details->status = 'complete';
        $payment_details->payment_status = 'complete';
        $payment_details->save();

        $customer_subject = __('Your appointment payment approved in').' '.get_static_option('site_'.get_user_lang().'_title');
        $admin_subject = __('Payment Approved successfully..!').' '.get_static_option('site_'.get_user_lang().'_title');

        try {
            Mail::to(get_static_option('tenant_site_global_email'))->send(new AppointmentMail($payment_details, $admin_subject,"admin"));
            Mail::to($payment_details->email)->send(new AppointmentMail( $payment_details, $customer_subject,'user'));

        } catch (\Exception $e) {
            return redirect()->back()->with(['type' => 'danger', 'msg' => $e->getMessage()]);
        }

        return redirect()->back()->with(['msg' => __('Payment Accepted Successfully..!'), 'type' => 'success']);
    }

}
