<?php

namespace App\Http\Controllers\Tenant\Admin;

use App\Helpers\DataTableHelpers\General;
use App\Helpers\DataTableHelpers\WeddingDatatable;
use App\Helpers\FlashMsg;
use App\Helpers\ResponseMessage;
use App\Helpers\SanitizeInput;
use App\Http\Controllers\Controller;
use App\Mail\WeddingMail;
use App\Models\WeddingPaymentLog;
use App\Models\WeddingPricePlan;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Modules\Donation\Helpers\DataTableHelpers\SubAppointmentDatatable;
use Yajra\DataTables\DataTables;

class WeddingPlanController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:wedding-price-plan-list|wedding-price-plan-edit|wedding-price-plan-delete',['only' => ['index']]);
        $this->middleware('permission:wedding-price-plan-create',['only' => ['store']]);
        $this->middleware('permission:wedding-price-plan-edit',['only' => ['update']]);
        $this->middleware('permission:wedding-price-plan-delete',['only' => ['delete']]);
    }

    public function index(Request $request){
        $default_lang = $request->lang ?? default_lang();
        $all_plans = WeddingPricePlan::orderBy('id','desc')->get();
        return view('tenant.admin.wedding-price-plan.price-plan-index',compact('all_plans','default_lang'));
    }

    public function store(Request $request){

        $this->validate($request,[
            'title' => 'required|string',
            'features' => 'required',
            'price' => 'required|numeric',
            'status' => 'required|integer',
            'not_available_features' => 'nullable',
        ]);

        $price_plan = new WeddingPricePlan();
        $price_plan->setTranslation('title',$request->lang,SanitizeInput::esc_html($request->title));
        $price_plan->setTranslation('features',$request->lang,SanitizeInput::esc_html($request->features));
        $price_plan->setTranslation('not_available_features',$request->lang,SanitizeInput::esc_html($request->not_available_features));
        $price_plan->price = $request->price;
        $price_plan->status = $request->status;
        $price_plan->is_popular = $request->is_popular;
        $price_plan->save();


        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function update(Request $request){

        $this->validate($request,[
            'title' => 'required|string',
            'features' => 'required',
            'price' => 'required|numeric',
            'status' => 'required|integer',
        ]);

        //create data for price plan
        $price_plan =  WeddingPricePlan::find($request->id);
        $price_plan->setTranslation('title',$request->lang,SanitizeInput::esc_html($request->title));
        $price_plan->setTranslation('features',$request->lang,SanitizeInput::esc_html($request->features));
        $price_plan->setTranslation('not_available_features',$request->lang,SanitizeInput::esc_html($request->not_available_features));
        $price_plan->price = $request->price;
        $price_plan->status = $request->status;
        $price_plan->is_popular = $request->is_popular;
        $price_plan->save();

        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function delete($id){

        $plan = WeddingPricePlan::findOrFail($id);
        $plan->delete();
        return response()->danger(ResponseMessage::delete());
    }

    public function bulk_action(Request $request)
    {
        WeddingPricePlan::whereIn('id',$request->ids)->delete();
        return response()->json(['status' => 'ok']);
    }


    public function wedding_payment_logs(Request $request)
    {

        if ($request->ajax()){
            $donation_logs =  WeddingPaymentLog::select('*')->orderBy('id','desc');
            return DataTables::of($donation_logs)
                ->addIndexColumn()
                ->addColumn('checkbox',function ($row){
                    return General::bulkCheckbox($row->id);
                })
                ->addColumn('info',function ($row){
                    return WeddingDatatable::paymentInfoColumn($row);
                })
                ->addColumn('status',function ($row){
                    return WeddingDatatable::get_wedding_status_with_markup($row);
                })
                ->addColumn('action', function($row){

                    $action = '';
                        $action .= General::deletePopover(route('tenant.admin.wedding.payment.log.delete',$row->id));

                        if($row->package_gateway == 'manual_payment_' && !empty($row->manual_payment_attachment)){
                            $action .= General::viewAttachment($row);
                        }

                    if($row->package_gateway == 'manual_payment_' && $row->payment_status == 'pending'){
                        $action .= General::paymentAccept($row->id,route('tenant.admin.wedding.payment.accept'));
                    }

                        if($row->status == 'complete'){
                            $action .= General::invoiceBtn(route('tenant.admin.wedding.invoice.generate'),$row->id);
                        }

                    if($row->payment_status == 'pending'){
                        $action .= WeddingDatatable::paymentAccept(route('tenant.admin.wedding.payment.accept',$row->id));
                    }

                    return $action;
                })
                ->rawColumns(['action','checkbox','info','status'])
                ->make(true);
        }
        return view('tenant.admin.wedding-price-plan.wedding-payment-logs-all');
    }

    public function wedding_payment_log_delete($id)
    {
       $log =  WeddingPaymentLog::findOrFail($id);
       $path = 'assets/uploads/attachment/'.$log->manual_payment_attachment;

        if(file_exists($path) && !is_dir($path)){
            unlink($path);
        }

        $log->delete();

        return redirect()->back()->with(['msg' => __('Wedding Payment Log Deleted..'), 'type' => 'danger']);
    }

    public function wedding_payment_log_bulk_action(Request $request)
    {
        WeddingPaymentLog::whereIn('id',$request->ids)->get();
        return response()->json(['status' => 'ok']);
    }

    public function wedding_invoice(Request $request)
    {
        $wedding_details = WeddingPaymentLog::find($request->id);
        if (empty($wedding_details)) {
            return abort(404);
        }

        $pdf = PDF::loadview('tenant.frontend.invoice.wedding',
            ['wedding_details' => $wedding_details])->setOptions(['defaultFont' => 'sans-serif','isRemoteEnabled'=> true
        ]);
        return $pdf->download('wedding-invoice.pdf');
    }

    public function wedding_payment_accept(Request $request)
    {
        $id = $request->id;
        $payment_log = WeddingPaymentLog::findOrFail($id);
        $payment_log->status = 'complete';
        $payment_log->payment_status = 'complete';
        $payment_log->save();

        $customer_subject = __('Your plan payment approved in').' '.get_static_option('site_'.get_user_lang().'_title');
        $admin_subject = __('Payment Approved successfully..!').' '.get_static_option('site_'.get_user_lang().'_title');

        try {
            Mail::to(get_static_option('tenant_site_global_email'))->send(new WeddingMail($payment_log, $admin_subject,"admin"));
            Mail::to($payment_log->email)->send(new WeddingMail( $payment_log, $customer_subject,'user'));

        } catch (\Exception $e) {
            return redirect()->back()->with(['type' => 'danger', 'msg' => $e->getMessage()]);
        }

        return redirect()->back()->with(FlashMsg::item_new(__('Payment Accepted Successfully..!')));

    }

}
