<?php

namespace Modules\SupportTicket\Http\Controllers\Landlord\Admin;

use App\Facades\GlobalLanguage;
use App\Helpers\ResponseMessage;
use App\Helpers\SanitizeInput;
use App\Http\Controllers\Controller;
use App\Models\SupportDepartment;
use Illuminate\Http\Request;

class SupportDepartmentController extends Controller
{
    private const BASE_PATH = 'supportticket::landlord.admin.support-ticket.';

    public function __construct()
    {
        $this->middleware('permission:support-ticket-department-list|support-ticket-department-create|support-ticket-department-edit|support-ticket-department-delete',['only' => ['category']]);
        $this->middleware('permission:support-ticket-department-create',['only' => ['new_category']]);
        $this->middleware('permission:support-ticket-department-edit',['only' => ['update']]);
        $this->middleware('permission:support-ticket-department-delete',['only' => ['delete','bulk_action']]);
    }

    public function category(Request $request){

        $all_category = SupportDepartment::all();
        return view(self::BASE_PATH.'category')->with([
            'all_category' => $all_category,
            'default_lang' => $request->lang ?? GlobalLanguage::default_slug()
        ]);
    }

    public function new_category(Request $request){

        $request->validate([
            'name' => 'required|string|max:191',
            'status' => 'nullable|string|max:191',
        ]);

        $service = new SupportDepartment();
        $service->setTranslation('name',$request->lang, SanitizeInput::esc_html($request->name));
        $service->status = $request->status;
        $service->save();

        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function update(Request $request){

        $request->validate([
            'name' => 'required|string|max:191',
            'status' => 'nullable|string|max:191',
        ]);

        $service = SupportDepartment::find($request->id);
        $service->setTranslation('name',$request->lang, SanitizeInput::esc_html($request->name));
        $service->status = $request->status;
        $service->save();

        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function delete(Request $request,$id){
        SupportDepartment::find($id)->delete();
        return response()->danger(ResponseMessage::delete('Service Deleted'));
    }

    public function bulk_action(Request $request){
        SupportDepartment::whereIn('id',$request->ids)->delete();
        return response()->json(['status' => 'ok']);
    }

}
