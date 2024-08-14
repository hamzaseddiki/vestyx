<?php

namespace Modules\Donation\Http\Controllers\Tenant\Admin;
use App\Facades\GlobalLanguage;
use App\Helpers\FlashMsg;
use App\Helpers\ResponseMessage;
use App\Mail\DonationMail;
use App\Models\Language;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Modules\Donation\Actions\Donation\DonationAdminAction;
use Modules\Donation\Entities\Donation;
use Modules\Donation\Entities\DonationCategory;
use Modules\Donation\Entities\DonationComment;
use Modules\Donation\Entities\DonationPaymentLog;
use Modules\Donation\Helpers\DataTableHelpers\DonationDatatable;
use Modules\Donation\Helpers\DataTableHelpers\General;
use Modules\Donation\Helpers\DataTableHelpers\PortfolioGeneral;
use Modules\Donation\Helpers\DataTableHelpers\SubAppointmentDatatable;
use Modules\Donation\Helpers\DataTableHelpers\SubAppointmentGeneral;
use Modules\Donation\Http\Requests\DonationInsertRequest;
use Yajra\DataTables\DataTables;


class DonationController extends Controller
{

    private const BASE_PATH = 'donation::tenant.backend.donations.';

    public function __construct()
    {
        $this->middleware('permission:donation-list|donation-create|donation-edit|donation-delete',['only' => 'index']);
        $this->middleware('permission:donation-create',['only' => 'create','store']);
        $this->middleware('permission:donation-edit',['only' => 'edit','update','clone']);
        $this->middleware('permission:donation-delete',['only' => 'delete','bulk_action','delete_all_comments','bulk_action_comments','donation_payment_log_bulk_action']);;
        $this->middleware('permission:donation-payment',['only' => 'donation_payment_logs']);
    }

    public function index(Request $request)
    {
        $default_lang = $request->lang ?? GlobalLanguage::default_slug();

        if ($request->ajax()){
            $data = Donation::usingLocale($default_lang)->select('*')->orderBy('id','desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('checkbox',function ($row){
                    return General::bulkCheckbox($row->id);
                })
                ->addColumn('info',function ($row) use ($default_lang){
                    return \Modules\Donation\Helpers\DataTableHelpers\DonationDatatable::infoColumn($row,$default_lang);
                })
                ->addColumn('image',function ($row){
                    return General::image($row->image);
                })
                ->addColumn('category',function ($row) use($default_lang){
                    return optional($row->category)->getTranslation('title',$default_lang);
                })
                ->addColumn('status',function($row){
                   return General::statusSpan($row->status);
                })
                ->addColumn('action', function($row){
                    $action = '';
                    $action.= General::viewIcon(route('tenant.frontend.donation.single',$row->slug));
                    $admin = auth()->guard('admin')->user();

                    if ($admin->can('donation-edit')){
                        $action .= General::editIcon(route('tenant.admin.donation.edit',$row->id));
                        $action .= General::cloneIcon(route('tenant.admin.donation.clone'),$row->id);
                        $action .= \Modules\Donation\Helpers\DataTableHelpers\DonationDatatable::comments($row->id);
                    }

                    if ($admin->can('donation-delete')){
                        $action .= General::deletePopover(route('tenant.admin.donation.delete',$row->id));
                    }
                    return $action;
                })
                ->rawColumns(['checkbox','image','info','action','status'])
                ->make(true);
        }
        return view(self::BASE_PATH . 'all-donations',compact('default_lang'));

    }

    public function create(Request $request)
    {
        $all_category = DonationCategory::select('id','title','slug','status')->where(['status' => 1])->get();
        return view(self::BASE_PATH . 'new-donation')->with([
            'all_category' => $all_category,
            'default_lang' => $request->lang ?? GlobalLanguage::default_slug(),
        ]);
    }

    public function store(DonationInsertRequest $request, DonationAdminAction $action)
    {
        $current_package = tenant()->user()->first()->payment_log()->firstOrFail()->package ?? [];
        $pages_count = Donation::count();
        $permission_page = $current_package->donation_permission_feature;

        if(!empty($permission_page) && $pages_count >= $permission_page){
            return response()->danger(ResponseMessage::delete(sprintf('You can not create donation above %d in this package',$permission_page)));
        }

       $response = $action->store_execute($request);
        return redirect()->back()->with($response);
    }

    public function edit($id)
    {
        $donation = Donation::findOrFail($id);
        $all_category = DonationCategory::all();
        return view(self::BASE_PATH . 'edit-donations')->with(['donation' => $donation, 'all_category' => $all_category]);
    }

    public function update(DonationInsertRequest $request, $id, DonationAdminAction $action)
    {
        $response = $action->update_execute($request,$id);
        return redirect()->back()->with($response);
    }

    public function delete($id)
    {
        $data = Donation::findOrFail($id);
        if(!empty($data->metainfo)){
          $data->metainfo()->delete();
        }
        $data->delete();
        return redirect()->back()->with(['msg' => __('Donation Deleted...'), 'type' => 'danger']);
    }

    public function clone(Request $request, DonationAdminAction $action)
    {
        $current_package = tenant()->user()->first()->payment_log()->firstOrFail()->package ?? [];
        $pages_count = Donation::count();
        $permission_page = $current_package->donation_permission_feature;

        if(!empty($permission_page) && $pages_count >= $permission_page){
            return response()->danger(ResponseMessage::delete(sprintf('You can not create donation above %d in this package',$permission_page)));
        }
       $response = $action->clone_execute($request);
        return redirect()->back()->with($response);
    }

    public function bulk_action(Request $request)
    {
        $logs = Donation::find($request->ids);

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
                'donation_raised_'.$lang->slug.'_text' => 'nullable|string',
                'donation_goal_'.$lang->slug.'_text' => 'nullable|string',

                'footer_contact_'.$lang->slug.'_title' => 'nullable|string',
                'footer_contact_'.$lang->slug.'_description' => 'nullable|string',
                'footer_contact_left_button_'.$lang->slug.'_text' => 'nullable|string',
                'footer_contact_right_button_'.$lang->slug.'_text' => 'nullable|string',
                'footer_contact_left_button_'.$lang->slug.'_url' => 'nullable|string',
                'footer_contact_right_button_'.$lang->slug.'_url' => 'nullable|string',
            ]);

            foreach ($fields as $key => $field) {
                update_static_option($key, $request->$key);
            }
        }

        $switcher_data = [
            'donation_custom_amount',
            'donation_default_amount',
            'donation_single_page_countdown_status',
            'donation_comments_show_hide',
            'donation_social_icons_show_hide',
            'donation_recent_donors_show_hide',
            'donation_custom_amount',
            'donation_faq_show_hide',
            'donation_alert_receiving_mail'
        ];

        foreach ($switcher_data as $data) {
            update_static_option($data, $request->$data);
        }

        return redirect()->back()->with(FlashMsg::settings_update());
    }


    public function type(){
        return response()->json(["success" => true]);
    }

    public function view_comments($id)
    {
        $donation = Donation::with('comments')->find($id);
        return view(self::BASE_PATH.'cause-comment',compact('donation'));
    }

    public function delete_all_comments(Request $request,$id){
        $category =  DonationComment::where('id',$id)->first();
        $category->delete();
        return response()->danger(ResponseMessage::delete());
    }

    public function bulk_action_comments(Request $request){
        DonationComment::whereIn('id',$request->ids)->delete();
        return response()->json(['status' => 'ok']);
    }

    public function donation_payment_logs(Request $request)
    {

        if ($request->ajax()){
            $donation_logs =  DonationPaymentLog::select('*')->orderBy('id','desc');
            return DataTables::of($donation_logs)
                ->addIndexColumn()
                ->addColumn('checkbox',function ($row){
                    return General::bulkCheckbox($row->id);
                })
                ->addColumn('info',function ($row){
                    return DonationDatatable::paymentInfoColumn($row);
                })
                ->addColumn('status',function ($row){
                    return DonationDatatable::get_donation_status_with_markup($row);
                })
                ->addColumn('action', function($row){
                    $admin = auth()->guard('admin')->user();
                    $action = '';
                    if ($admin->can('donation-payment-delete')){
                        $action .= General::deletePopover(route('tenant.admin.donation.payment.log.delete',$row->id));
                    }
                    if ($admin->can('donation-payment-edit')){

                        if($row->payment_gateway == 'manual_payment_' && !empty($row->manual_payment_attachment)){
                            $action .= General::viewAttachment($row);
                        }
                        if($row->status == 1){
                            $action .= General::invoiceBtn(route('tenant.admin.donation.invoice.generate'),$row->id);
                        }

                        if($row->status == 0){
                            $action .= General::paymentAccept(route('tenant.admin.donation.payment.accept',$row->id));
                        }

                    }

                    return $action;
                })
                ->rawColumns(['action','checkbox','info','status'])
                ->make(true);
        }
        return view(self::BASE_PATH . 'payment-data.donation-payment-logs-all');
    }

    public function donation_payment_logs_report(Request $request)
    {
        $order_data = '';
        $query = DonationPaymentLog::query();
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

        return view(self::BASE_PATH . 'payment-data.donation-report')->with([
            'order_data' => $order_data,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'items' => $request->items,
            'payment_status' => $request->status,
            'error_msg' => $error_msg
        ]);
    }

    public function donation_payment_log_delete($id)
    {
        $log = DonationPaymentLog::findOrFail($id);
        $cause = Donation::find($log->donation_id);
        $cause->raised = ($cause->raised - $log->amount);
        $cause->save();
        $log->delete();
        return redirect()->back()->with(['msg' => __('Donation Payment Log Deleted..'), 'type' => 'danger']);
    }

    public function donation_payment_log_bulk_action(Request $request)
    {
        $logs = DonationPaymentLog::whereIn('id',$request->ids)->get();

        foreach ($logs as $log){
            $cause = Donation::find($log->donation_id);
            $cause->raised = ($cause->raised - $log->amount);
            $cause->save();
            $log->delete();
        }

        return response()->json(['status' => 'ok']);
    }

    public function donation_invoice(Request $request)
    {
        $donation_details = DonationPaymentLog::find($request->id);
        if (empty($donation_details)) {
            return abort(404);
        }

        $pdf = PDF::loadview('tenant.frontend.invoice.donation',
            ['donation_details' => $donation_details])->setOptions(['defaultFont' => 'sans-serif','isRemoteEnabled'=> true
        ]);
        return $pdf->download('donation-invoice.pdf');
    }

    public function donation_payment_accept($id)
    {
        $payment_details = DonationPaymentLog::findOrFail($id);
        $payment_details->status = 1;
        $payment_details->save();

        $customer_subject = __('Your donation payment approved in').' '.get_static_option('site_'.get_user_lang().'_title');
        $admin_subject = __('Payment Approved successfully..!').' '.get_static_option('site_'.get_user_lang().'_title');

        try {
            Mail::to(get_static_option('tenant_site_global_email'))->send(new DonationMail($payment_details, $admin_subject,"admin"));
            Mail::to($payment_details->email)->send(new DonationMail( $payment_details, $customer_subject,'user'));

        } catch (\Exception $e) {
            return redirect()->back()->with(['type' => 'danger', 'msg' => $e->getMessage()]);
        }

        return redirect()->back()->with(['msg' => __('Payment Accepted Successfully..!'), 'type' => 'success']);
    }




}
