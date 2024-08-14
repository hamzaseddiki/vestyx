<?php

namespace Modules\Appointment\Http\Controllers\Tenant\Admin;

use App\Helpers\ResponseMessage;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Appointment\Entities\AppointmentDay;
use Modules\Appointment\Entities\AppointmentDayType;
use Modules\Appointment\Entities\AppointmentSchedule;
use Modules\Blog\Entities\Blog;


class AppointmentScheduleController extends Controller
{
    public function index(Request $request)
    {
        $default_lang = $request->lang ?? default_lang();
        $all_times = AppointmentSchedule::all() ?? [];
        $days = AppointmentDay::all();
        $day_types = AppointmentDayType::all();
        return view('appointment::tenant.backend.appointment-section.appointment-schedules',compact('all_times','default_lang','days','day_types'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'start_time'=> 'required',
            'end_time'=> 'required',
            'day_id'=> 'required',
            'day_type'=> 'required',
        ]);


        $time_modify = $request->start_time. ' - ' . $request->end_time;

        $category = new AppointmentSchedule();
        $category->day_id = $request->day_id;
        $category->time = $time_modify;
        $category->allow_multiple = $request->allow_multiple;
        $category->day_type = $request->day_type;
        $category->status = $request->status;
        $category->save();

        return response()->success(ResponseMessage::SettingsSaved());
    }


    public function update(Request $request)
    {
        $request->validate([
            'start_time'=> 'required',
            'end_time'=> 'required',
            'day_id'=> 'required',
        ]);

        $category =  AppointmentSchedule::findOrFail($request->id);
        $time_modify = $request->start_time. ' - ' . $request->end_time;

        $category->day_id = $request->day_id;
        $category->time = $time_modify;
        $category->allow_multiple = $request->allow_multiple;
        $category->status = $request->status;
        $category->day_type = $request->day_type;
        $category->save();

        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function destroy($id)
    {
        $category = AppointmentSchedule::where('id',$id)->first();
        $category->delete();

        return response()->danger(ResponseMessage::delete());
    }

    public function bulk_action(Request $request){
        AppointmentSchedule::whereIn('id',$request->ids)->delete();
        return response()->json(['status' => 'ok']);
    }
}
