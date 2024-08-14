<?php

namespace Modules\Appointment\Http\Controllers\Tenant\Admin;

use App\Helpers\ResponseMessage;
use App\Helpers\SanitizeInput;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Appointment\Entities\AppointmentCategory;
use Modules\Appointment\Entities\AppointmentDay;
use Modules\Appointment\Entities\AppointmentSubcategory;
use Modules\Blog\Entities\BlogCategory;

class AppointmentSubCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:appointment-sub-category-list|appointment-sub-category-create|appointment-sub-category-edit|appointment-sub-category-delete',['only' => 'index']);
        $this->middleware('permission:appointment-sub-category-create',['only' => 'store']);
        $this->middleware('permission:appointment-sub-category-edit',['only' => 'update']);
        $this->middleware('permission:appointment-sub-category-delete',['only' => 'destroy','bulk_action']);
    }
    public function index(Request $request)
    {
        $default_lang = $request->lang ?? default_lang();
        $all_categories = AppointmentCategory::all();
        $all_subcategories = AppointmentSubcategory::all();

        return view('appointment::tenant.backend.appointment-section.sub-category',compact('all_categories','all_subcategories','default_lang'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'appointment_category_id'=> 'required',
            'title'=> 'required|max:191',
        ]);

        $category = new AppointmentSubcategory();
        $category->setTranslation('title',$request->lang, SanitizeInput::esc_html($request->title));
        $category->appointment_category_id = $request->appointment_category_id;
        $category->status = $request->status;
        $category->save();

        return response()->success(ResponseMessage::SettingsSaved());
    }


    public function update(Request $request)
    {
        $request->validate([
            'appointment_category_id'=> 'required',
            'title'=> 'required|max:191',
        ]);

        $category =  AppointmentSubcategory::findOrFail($request->id);
        $category->setTranslation('title',$request->lang, SanitizeInput::esc_html($request->title));
        $category->appointment_category_id = $request->appointment_category_id;
        $category->status = $request->status;
        $category->save();

        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function destroy($id)
    {
        $category =  AppointmentSubcategory::where('id',$id)->first();
        $category->delete();

        return response()->danger(ResponseMessage::delete());
    }

    public function bulk_action(Request $request){
        AppointmentSubcategory::whereIn('id',$request->ids)->delete();
        return response()->json(['status' => 'ok']);
    }
}
