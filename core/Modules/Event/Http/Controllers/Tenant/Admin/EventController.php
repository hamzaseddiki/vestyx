<?php

namespace Modules\Event\Http\Controllers\Tenant\Admin;
use App\Facades\GlobalLanguage;
use App\Helpers\FlashMsg;
use App\Helpers\ResponseMessage;
use App\Mail\EventMail;
use App\Models\Language;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Modules\Appointment\Helpers\DataTableHelpers\SubAppointmentGeneral;
use Modules\Event\Actions\Event\EventAdminAction;
use Modules\Event\Entities\Event;
use Modules\Event\Entities\EventCategory;
use Modules\Event\Entities\EventComment;
use Modules\Event\Entities\EventPaymentLog;
use Modules\Event\Helpers\DataTableHelpers\EventDatatable;
use Modules\Event\Helpers\DataTableHelpers\EventGeneral;
use Modules\Event\Http\Requests\EventRequest;
use Yajra\DataTables\DataTables;


class EventController extends Controller
{

    private const BASE_PATH = 'event::tenant.backend.events.';

    public function __construct()
    {
        $this->middleware('permission:event-list|event-create|event-edit|event-delete',['only' => 'index']);
        $this->middleware('permission:event-create',['only' => 'create','store']);
        $this->middleware('permission:event-edit',['only' => 'edit','update','clone']);
        $this->middleware('permission:event-delete',['only' => 'delete','bulk_action','delete_all_comments','bulk_action_comments','event_payment_log_delete']);
        /* ==== event payment log ====*/
        $this->middleware('permission:event-payment',['only' => 'event_payment_logs']);

    }

    public function index(Request $request)
    {
        $default_lang = $request->lang ?? GlobalLanguage::default_slug();
        if ($request->ajax()){
            $data = Event::usingLocale($default_lang)->select('*')->orderBy('id','desc')->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('checkbox',function ($row){
                    return EventGeneral::bulkCheckbox($row->id);
                })
                ->addColumn('info',function ($row) use ($default_lang){
                    return EventDatatable::infoColumn($row,$default_lang);
                })
                ->addColumn('image',function ($row){
                    return EventGeneral::image($row->image);
                })
                ->addColumn('category',function ($row) use($default_lang){
                    return optional($row->category)->getTranslation('title',$default_lang);
                })
                ->addColumn('status',function($row){
                   return EventGeneral::statusSpan($row->status);
                })
                ->addColumn('action', function($row){
                    $action = '';
                    $route = route('tenant.frontend.event.single',$row->slug);
                    $action.= EventGeneral::viewIcon($route);
                    $admin = auth()->guard('admin')->user();

                    if ($admin->can('event-edit')){
                        $action .= EventGeneral::editIcon(route('tenant.admin.event.edit',$row->id));
                        $action .= EventGeneral::cloneIcon(route('tenant.admin.event.clone'),$row->id);
                    }
                    if ($admin->can('event-delete')){
                        $action .= EventGeneral::deletePopover(route('tenant.admin.event.delete',$row->id));
                    }
                    $action.= '<br>';
                    $action .= EventDatatable::comments($row->id);
                    return $action;
                })
                ->rawColumns(['checkbox','image','info','action','status'])
                ->make(true);
        }
        return view(self::BASE_PATH . 'all-events',compact('default_lang'));
    }


    public function create(Request $request)
    {
        $all_category = EventCategory::select('id','title','status')->where(['status' => 1])->get();
        return view(self::BASE_PATH . 'new-event')->with([
            'all_category' => $all_category,
            'default_lang' => $request->lang ?? GlobalLanguage::default_slug(),
        ]);
    }

    public function store(EventRequest $request, EventAdminAction $action)
    {
        $current_package = tenant()->user()->first()->payment_log()->firstOrFail()->package ?? [];
        $pages_count = Event::count();
        $permission_page = $current_package->event_permission_feature;

        if(!empty($permission_page) && $pages_count >= $permission_page){
            return response()->danger(ResponseMessage::delete(sprintf('You can not create event above %d in this package',$permission_page)));
        }

        $response = $action->store_execute($request);
        return redirect()->back()->with($response);
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);
        $all_category = EventCategory::select('id','title')->get();
        return view(self::BASE_PATH . 'edit-event')->with(['event' => $event, 'all_category' => $all_category]);
    }

    public function update(EventRequest $request, $id, EventAdminAction $action)
    {
        $response = $action->update_execute($request,$id);
        return redirect()->back()->with($response);
    }

    public function delete($id)
    {
        $data = Event::findOrFail($id);
        if(!empty($data->metainfo)){
          $data->metainfo()->delete();
        }
        $data->delete();
        return redirect()->back()->with(['msg' => __('Event Deleted...'), 'type' => 'danger']);
    }

    public function clone(Request $request, EventAdminAction $action)
    {

        $current_package = tenant()->user()->first()->payment_log()->firstOrFail()->package ?? [];
        $pages_count = Event::count();
        $permission_page = $current_package->event_permission_feature;

        if(!empty($permission_page) && $pages_count >= $permission_page){
            return response()->danger(ResponseMessage::delete(sprintf('You can not create event above %d in this package',$permission_page)));
        }
       $response = $action->clone_execute($request);
        return redirect()->back()->with($response);
    }

    public function bulk_action(Request $request)
    {
        $logs = Event::find($request->ids);

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
                'event_map_area_'.$lang->slug.'_title' => 'nullable|string',
                'event_chart_area_'.$lang->slug.'_title' => 'nullable|string',
                'event_social_area_'.$lang->slug.'_title' => 'nullable|string',
                'event_category_area_'.$lang->slug.'_title' => 'nullable|string',
                'event_tab_description_'.$lang->slug.'_title' => 'nullable|string',
                'event_tab_comment_'.$lang->slug.'_title' => 'nullable|string',
                'event_tab_book_'.$lang->slug.'_title' => 'nullable|string',
            ]);

            foreach ($fields as $key => $field) {
                update_static_option($key, $request->$key);
            }
        }

        $switcher_data = [
            'event_map_area_show_hide',
            'event_chart_area_show_hide',
            'event_social_area_show_hide',
            'event_category_area_show_hide',
            'event_related_area_show_hide',
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
        $event = Event::with('comments')->find($id);
        return view(self::BASE_PATH.'event-comment',compact('event'));
    }

    public function delete_all_comments(Request $request,$id){
        $category =  EventComment::where('id',$id)->first();
        $category->delete();
        return response()->danger(ResponseMessage::delete());
    }

    public function bulk_action_comments(Request $request){
        EventComment::whereIn('id',$request->ids)->delete();
        return response()->json(['status' => 'ok']);
    }

    public function event_payment_logs(Request $request)
    {

        if ($request->ajax()){
            $donation_logs =  EventPaymentLog::select('*')->orderBy('id','desc');
            return DataTables::of($donation_logs)
                ->addIndexColumn()
                ->addColumn('checkbox',function ($row){
                    return SubAppointmentGeneral::bulkCheckbox($row->id);
                })
                ->addColumn('info',function ($row){
                    return EventDatatable::paymentInfoColumn($row);
                })
                ->addColumn('status',function ($row){
                    return EventDatatable::get_event_status_with_markup($row);
                })
                ->addColumn('action', function($row){
                    $admin = auth()->guard('admin')->user();
                    $action = '';
                    if ($admin->can('event-payment-delete')){
                        $action .= SubAppointmentGeneral::deletePopover(route('tenant.admin.event.payment.log.delete',$row->id));
                    }
                    if ($admin->can('event-payment-edit')){

                        if($row->payment_gateway == 'manual_payment_' && !empty($row->manual_payment_attachment)){
                            $action .= SubAppointmentGeneral::viewAttachment($row);
                        }
                        if($row->status == 1){
                            $action .= SubAppointmentGeneral::invoiceBtn(route('tenant.admin.event.invoice.generate'),$row->id);
                        }

                        if($row->status == 0){
                            $action .= SubAppointmentGeneral::paymentAccept(route('tenant.admin.event.payment.accept',$row->id));
                        }

                    }

                    return $action;
                })
                ->rawColumns(['action','checkbox','info','status'])
                ->make(true);
        }
        return view(self::BASE_PATH . 'payment-data.event-payment-logs-all');
    }

    public function event_payment_logs_report(Request $request)
    {
        $order_data = '';
        $query = EventPaymentLog::query();
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

        return view(self::BASE_PATH .'payment-data.event-report')->with([
            'order_data' => $order_data,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'items' => $request->items,
            'payment_status' => $request->status,
            'error_msg' => $error_msg
        ]);
    }

    public function event_payment_log_delete($id)
    {
        EventPaymentLog::findOrFail($id)->delete();
        return redirect()->back()->with(['msg' => __('Event Payment Log Deleted..'), 'type' => 'danger']);
    }

    public function event_payment_log_bulk_action(Request $request)
    {
        EventPaymentLog::whereIn('id',$request->ids)->delete();
        return response()->json(['status' => 'ok']);
    }

    public function event_invoice(Request $request)
    {
        $event_details = EventPaymentLog::find($request->id);
        if (empty($event_details)) {
            return abort(404);
        }

        $pdf = PDF::loadview('tenant.frontend.invoice.event', ['event_details' => $event_details])
            ->setOptions(['defaultFont' => 'sans-serif','isRemoteEnabled'=>true]);
        return $pdf->download('event-invoice.pdf');
    }

    public function event_payment_accept($id)
    {
        $payment_details = EventPaymentLog::findOrFail($id);
        $payment_details->status = 1;
        $payment_details->save();

        $customer_subject = __('Your event payment approved in').' '.get_static_option('site_'.get_user_lang().'_title');
        $admin_subject = __('Payment Approved successfully..!').' '.get_static_option('site_'.get_user_lang().'_title');

        try {
            Mail::to(get_static_option('tenant_site_global_email'))->send(new EventMail($payment_details, $admin_subject,"admin"));
            Mail::to($payment_details->email)->send(new EventMail( $payment_details, $customer_subject,'user'));

        } catch (\Exception $e) {
            return redirect()->back()->with(['type' => 'danger', 'msg' => $e->getMessage()]);
        }

        return redirect()->back()->with(['msg' => __('Payment Accepted Successfully..!'), 'type' => 'success']);
    }

}
