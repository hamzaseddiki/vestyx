<?php

namespace Modules\Appointment\Http\Controllers\Tenant\Admin;

use App\Facades\GlobalLanguage;
use App\Helpers\DataTableHelpers\General;
use App\Helpers\FlashMsg;
use App\Helpers\ResponseMessage;
use App\Models\Language;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Appointment\Actions\Appointment\AppointmentAdminAction;
use Modules\Appointment\Entities\Appointment;
use Modules\Appointment\Entities\AppointmentCategory;
use Modules\Appointment\Entities\AppointmentComment;
use Modules\Appointment\Entities\AppointmentSubcategory;
use Modules\Appointment\Entities\AppointmentTax;
use Modules\Appointment\Entities\SubAppointment;
use Modules\Appointment\Helpers\DataTableHelpers\AppointmentDatatable;
use Modules\Appointment\Helpers\DataTableHelpers\AppointmentGeneral;
use Modules\Appointment\Http\Requests\AppointmentRequest;
use Yajra\DataTables\DataTables;

class AppointmentController extends Controller
{
    private const BASE_PATH = 'appointment::tenant.backend.appointment-section.appointment.';

    public function __construct()
    {
        $this->middleware('permission:appointment-list|appointment-create|appointment-edit|appointment-delete',['only' => 'index']);
        $this->middleware('permission:appointment-create',['only' => 'create','store']);
        $this->middleware('permission:appointment-edit',['only' => 'edit','update','clone']);
        $this->middleware('permission:appointment-delete',['only' => 'delete','bulk_action']);;
    }

    public function index(Request $request)
    {
        $default_lang = $request->lang ?? GlobalLanguage::default_slug();

        if ($request->ajax()){
            $data = Appointment::usingLocale($default_lang)->select('*')->orderBy('id','desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('checkbox',function ($row){
                    return AppointmentGeneral::bulkCheckbox($row->id);
                })
                ->addColumn('info',function ($row) use ($default_lang){
                    return AppointmentDatatable::infoColumn($row,$default_lang);
                })
                ->addColumn('image',function ($row){
                    return AppointmentGeneral::image($row->image);
                })
                ->addColumn('status',function($row){
                    return AppointmentGeneral::statusSpan($row->status);
                })
                ->addColumn('action', function($row){
                    $action = '';
                    $action.= AppointmentGeneral::viewIcon(route('tenant.frontend.appointment.order.page',$row->slug));
                    $admin = auth()->guard('admin')->user();

                    if ($admin->can('appointment-edit')){
                        $action .= AppointmentGeneral::editIcon(route('tenant.admin.appointment.edit',$row->id));
                        $action .= AppointmentGeneral::cloneIcon(route('tenant.admin.appointment.clone'),$row->id);

                    }
                    if ($admin->can('appointment-delete')){
                        $action .= AppointmentGeneral::deletePopover(route('tenant.admin.appointment.delete',$row->id));
                    }

                    $action.= '<br>';
                    $action .= AppointmentGeneral::viewComments(route(route_prefix().'admin.appointment.comments.view',$row->id),$row->id);
                    return $action;
                })
                ->rawColumns(['checkbox','image','info','action','status'])
                ->make(true);
        }
        return view(self::BASE_PATH . 'all-appointments',compact('default_lang'));

    }

    public function create(Request $request)
    {
        $all_sub_appointments = SubAppointment::select('id','title','price','person')->get();
        $all_categories = AppointmentCategory::all();
        $all_subcategories = AppointmentSubcategory::all();
        return view(self::BASE_PATH . 'new-appointment')->with([
            'default_lang' => $request->lang ?? GlobalLanguage::default_slug(),
            'all_sub_appointments'=>$all_sub_appointments,
            'all_categories'=>$all_categories,
            'all_subcategories'=>$all_subcategories,
        ]);
    }

    public function store(AppointmentRequest $request, AppointmentAdminAction $action)
    {
        $current_package = tenant()->payment_log()->first()?->package ?? [];
        $pages_count = Appointment::count();
        $permission_page = $current_package->appointment_permission_feature;

        if(!empty($permission_page) && $pages_count >= $permission_page){
            return response()->danger(ResponseMessage::delete(sprintf('You can not create appointment above %d in this package',$permission_page)));
        }

        $response = $action->store_execute($request);
        return redirect()->back()->with($response);
    }

    public function edit($id)
    {
        $appointment = Appointment::with("additional_appointments")->findOrFail($id);
        $all_sub_appointments = SubAppointment::select('id','title','price','person')->get();

        $tax_info = [];

        if(!empty($appointment->tax_status)){
            $tax_info = AppointmentTax::where('appointment_id',$appointment->id)->first();
        }

        $all_categories = AppointmentCategory::all();
        $all_subcategories = AppointmentSubcategory::all();

        return view(self::BASE_PATH . 'edit-appointment')->with([
            'appointment' => $appointment,
            'all_sub_appointments'=> $all_sub_appointments,
            'all_categories'=> $all_categories,
            'all_subcategories'=> $all_subcategories,
            'tax_info'=> $tax_info,
        ]);
    }

    public function update(AppointmentRequest $request, $id, AppointmentAdminAction $action)
    {

        $response = $action->update_execute($request,$id);
        return redirect()->back()->with($response);
    }

    public function delete($id)
    {
        $data = Appointment::findOrFail($id);
        if(!empty($data->metainfo)){
            $data->metainfo()->delete();
        }
        $data->delete();
        return redirect()->back()->with(['msg' => __('Appointment Deleted...'), 'type' => 'danger']);
    }

    public function clone(Request $request, AppointmentAdminAction $action)
    {
        $current_package = tenant()->payment_log()->first()?->package ?? [];
        $pages_count = Appointment::count();
        $permission_page = $current_package->appointment_permission_feature;

        if(!empty($permission_page) && $pages_count >= $permission_page){
            return response()->danger(ResponseMessage::delete(sprintf('You can not create appointment above %d in this package',$permission_page)));
        }
        $response = $action->clone_execute($request);
        return redirect()->back()->with($response);
    }

    public function bulk_action(Request $request)
    {
        $logs = Appointment::find($request->ids);

        foreach ($logs as $data){
            if(!empty($data->metainfo)){
                $data->metainfo()->delete();
            }
            $data->delete();
        }
        return response()->json(['status' => 'ok']);
    }

    public function sub_category_via_ajax( Request $request)
    {
        $category_id = $request->category_id;
        $lang = $request->lang;


        $markup = '';
        if(!empty($category_id)){
            $all_subcategories = AppointmentSubcategory::where('appointment_category_id',$category_id)->get();

            foreach ($all_subcategories as $subcat){
                $id = $subcat->id;
                $title = $subcat->getTranslation('title',$lang);
    $markup.= <<<OPTION
         <option value="{$id}">{$title}</option>
OPTION;
            }
        }

        return response()->json($markup);
    }

    public function view_comments($id)
    {
        $donation = Appointment::with('comments')->find($id);
        return view(self::BASE_PATH.'appointment-comment',compact('donation'));
    }

    public function delete_all_comments(Request $request,$id){
        $category =  AppointmentComment::where('id',$id)->first();
        $category->delete();
        return response()->danger(ResponseMessage::delete());
    }

    public function bulk_action_comments(Request $request){
        AppointmentComment::whereIn('id',$request->ids)->delete();
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
                'appointment_order_page_sub_appointment_'.$lang->slug.'_heading' => 'nullable|string',
                'appointment_order_page_date_section_'.$lang->slug.'_heading' => 'nullable|string',
                'appointment_order_page_date_selection_button_'.$lang->slug.'_text' => 'nullable|string',

                'appointment_payment_page_left_'.$lang->slug.'_heading' => 'nullable|string',
                'appointment_payment_page_left_button_'.$lang->slug.'_text' => 'nullable|string',
                'appointment_payment_page_bottom_'.$lang->slug.'_title' => 'nullable|string',
                'appointment_payment_page_bottom_'.$lang->slug.'_phone' => 'nullable|string',
                'appointment_payment_page_bottom_'.$lang->slug.'_description' => 'nullable|string',
                'appointment_payment_page_right_'.$lang->slug.'_heading' => 'nullable|string',
                'appointment_payment_page_right_pay_button_'.$lang->slug.'_text' => 'nullable|string',

            ]);

            foreach ($fields as $key => $field) {
                update_static_option($key, $request->$key);
            }
        }



        $switcher_data = [
            'appointment_tax_apply_status'
        ];

        foreach ($switcher_data as $data) {
            update_static_option($data, $request->$data);
        }

        return redirect()->back()->with(FlashMsg::settings_update());
    }
}
