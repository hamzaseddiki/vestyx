<?php

namespace Modules\Appointment\Http\Controllers\Tenant\Admin;

use App\Helpers\ResponseMessage;
use App\Helpers\SanitizeInput;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Appointment\Entities\AppointmentCategory;
use Modules\Appointment\Entities\AppointmentDay;
use Modules\Blog\Entities\BlogCategory;

class AppointmentCategoryController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:appointment-category-list|appointment-category-create|appointment-category-edit|appointment-category-delete',['only' => 'index']);
        $this->middleware('permission:appointment-category-create',['only' => 'store']);
        $this->middleware('permission:appointment-category-edit',['only' => 'update']);
        $this->middleware('permission:appointment-category-delete',['only' => 'destroy','bulk_action']);
    }

    public function index(Request $request)
    {
        $default_lang = $request->lang ?? default_lang();
        $all_categories = AppointmentCategory::select('id','title','status','created_at')->get();
        return view('appointment::tenant.backend.appointment-section.category',compact('all_categories','default_lang'));
    }

    public function store(Request $request)
    {
        $request->validate(['title'=> 'required|max:191']);

        $category = new AppointmentCategory();
        $category->setTranslation('title',$request->lang, SanitizeInput::esc_html($request->title));
        $category->status = $request->status;
        $category->save();

        return response()->success(ResponseMessage::SettingsSaved());
    }


    public function update(Request $request)
    {
        $request->validate(['title'=> 'required|max:191']);

        $category =  AppointmentCategory::findOrFail($request->id);
        $category->setTranslation('title',$request->lang, SanitizeInput::esc_html($request->title));
        $category->status = $request->status;
        $category->save();

        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function destroy($id)
    {
        $category =  AppointmentCategory::where('id',$id)->first();
        $category->delete();

        return response()->danger(ResponseMessage::delete());
    }

    public function bulk_action(Request $request){
        AppointmentCategory::whereIn('id',$request->ids)->delete();
        return response()->json(['status' => 'ok']);
    }
}
