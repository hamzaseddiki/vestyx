<?php

namespace Modules\Appointment\Http\Controllers\Tenant\Admin;

use App\Helpers\ResponseMessage;
use App\Helpers\SanitizeInput;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Appointment\Entities\AppointmentDay;
use Modules\Appointment\Entities\AppointmentDayType;
use Modules\Blog\Entities\BlogCategory;

class AppointmentDayTypeController extends Controller
{
    public function index(Request $request)
    {
        $default_lang = $request->lang ?? default_lang();
        $all_day_types = AppointmentDayType::select('id','title','status')->get();
        return view('appointment::tenant.backend.appointment-section.appointment-day-types',compact('all_day_types','default_lang'));
    }

    public function store(Request $request)
    {
        $request->validate(['title'=> 'required|max:191']);
        $category = new AppointmentDayType();
        $category->setTranslation('title',$request->lang, SanitizeInput::esc_html($request->title));
        $category->status = $request->status;
        $category->save();

        return response()->success(ResponseMessage::SettingsSaved());
    }


    public function update(Request $request)
    {
        $request->validate(['title'=> 'required|max:191']);

        $category =  AppointmentDayType::findOrFail($request->id);
        $category->setTranslation('title',$request->lang, SanitizeInput::esc_html($request->title));
        $category->status = $request->status;
        $category->save();

        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function destroy($id)
    {
        $category =  AppointmentDayType::where('id',$id)->first();
        $category->delete();

        return response()->danger(ResponseMessage::delete());
    }

    public function bulk_action(Request $request){
        AppointmentDayType::whereIn('id',$request->ids)->delete();
        return response()->json(['status' => 'ok']);
    }
}
