<?php

namespace Modules\Appointment\Http\Controllers\Tenant\Admin;

use App\Facades\GlobalLanguage;
use App\Helpers\ResponseMessage;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Appointment\Actions\Appointment\SubAppointmentAction;
use Modules\Appointment\Entities\SubAppointment;
use Modules\Appointment\Entities\SubAppointmentComment;
use Modules\Appointment\Helpers\DataTableHelpers\SubAppointmentDatatable;
use Modules\Appointment\Helpers\DataTableHelpers\SubAppointmentGeneral;
use Modules\Appointment\Http\Requests\SubAppointmentRequest;
use Yajra\DataTables\DataTables;

class SubAppointmentController extends Controller
{
    private const BASE_PATH = 'appointment::tenant.backend.appointment-section.sub-appointment.';

    public function __construct()
    {
        $this->middleware('permission:sub-appointment-list|sub-appointment-create|sub-appointment-edit|sub-appointment-delete',['only' => 'index']);
        $this->middleware('permission:sub-appointment-create',['only' => 'create','store']);
        $this->middleware('permission:sub-appointment-edit',['only' => 'edit','update','clone']);
        $this->middleware('permission:sub-appointment-delete',['only' => 'delete','bulk_action']);;
    }

    public function index(Request $request)
    {
        $default_lang = $request->lang ?? GlobalLanguage::default_slug();

        if ($request->ajax()){
            $data = SubAppointment::usingLocale($default_lang)->select('*')->orderBy('id','desc')->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('checkbox',function ($row){
                    return SubAppointmentGeneral::bulkCheckbox($row->id);
                })
                ->addColumn('info',function ($row) use ($default_lang){
                    return SubAppointmentDatatable::infoColumn($row,$default_lang);
                })
                ->addColumn('image',function ($row){
                    return SubAppointmentGeneral::image($row->image);
                })
                ->addColumn('status',function($row){
                    return SubAppointmentGeneral::statusSpan($row->status);
                })
                ->addColumn('action', function($row){
                    $action = '';
                    $action.= SubAppointmentGeneral::viewIcon(route('tenant.frontend.sub.appointment.single',$row->slug));
                        $action .= SubAppointmentGeneral::editIcon(route('tenant.admin.sub.appointment.edit',$row->id));
                        $action .= SubAppointmentGeneral::cloneIcon(route('tenant.admin.sub.appointment.clone'),$row->id);
                        $action .= SubAppointmentGeneral::deletePopover(route('tenant.admin.sub.appointment.delete',$row->id));
                    $action.= '<br>';
                    $action .= SubAppointmentGeneral::viewComments(route(route_prefix().'admin.sub.appointment.comments.view',$row->id),$row->id);

                    return $action;
                })
                ->rawColumns(['checkbox','image','info','action','status'])
                ->make(true);
        }
        return view(self::BASE_PATH . 'all-sub-appointments',compact('default_lang'));

    }

    public function create(Request $request)
    {
        return view(self::BASE_PATH . 'new-sub-appointment')->with([
            'default_lang' => $request->lang ?? GlobalLanguage::default_slug(),
        ]);
    }

    public function store(SubAppointmentRequest $request, SubAppointmentAction $action)
    {
//        $current_package = tenant()->user()->first()->payment_log()->firstOrFail()->package ?? [];
//        $pages_count = Donation::count();
//        $permission_page = $current_package->donation_permission_feature;
//
//        if(!empty($permission_page) && $pages_count >= $permission_page){
//            return response()->danger(ResponseMessage::delete(sprintf('You can not create donation above %d in this package',$permission_page)));
//        }

        $response = $action->store_execute($request);
        return redirect()->back()->with($response);
    }

    public function edit($id)
    {
        $sub_appointment = SubAppointment::findOrFail($id);
        return view(self::BASE_PATH . 'edit-sub-appointment')->with(['sub_appointment' => $sub_appointment]);
    }

    public function update(SubAppointmentRequest $request, $id, SubAppointmentAction $action)
    {
        $response = $action->update_execute($request,$id);
        return redirect()->back()->with($response);
    }

    public function delete($id)
    {
        $data = SubAppointment::findOrFail($id);
        if(!empty($data->metainfo)){
            $data->metainfo()->delete();
        }
        $data->delete();
        return redirect()->back()->with(['msg' => __('Sub Appointment Deleted...'), 'type' => 'danger']);
    }

    public function clone(Request $request, SubAppointmentAction $action)
    {
//        $current_package = tenant()->user()->first()->payment_log()->firstOrFail()->package ?? [];
//        $pages_count = Donation::count();
//        $permission_page = $current_package->donation_permission_feature;
//
//        if(!empty($permission_page) && $pages_count >= $permission_page){
//            return response()->danger(ResponseMessage::delete(sprintf('You can not create donation above %d in this package',$permission_page)));
//        }
        $response = $action->clone_execute($request);
        return redirect()->back()->with($response);
    }

    public function bulk_action(Request $request)
    {
        $logs = SubAppointment::find($request->ids);

        foreach ($logs as $data){
            if(!empty($data->metainfo)){
                $data->metainfo()->delete();
            }
            $data->delete();
        }
        return response()->json(['status' => 'ok']);
    }


    public function type(){
        return response()->json(["success" => true]);
    }

    public function view_comments($id)
    {
        $donation = SubAppointment::with('comments')->findOrFail($id);
        return view(self::BASE_PATH.'sub-appointment-comment',compact('donation'));
    }

    public function delete_all_comments(Request $request,$id){
        $category =  SubAppointmentComment::where('id',$id)->firstOrFail();
        $category->delete();
        return response()->danger(ResponseMessage::delete());
    }

    public function bulk_action_comments(Request $request){
        SubAppointmentComment::whereIn('id',$request->ids)->delete();
        return response()->json(['status' => 'ok']);
    }

}
