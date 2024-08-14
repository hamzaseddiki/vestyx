<?php

namespace Modules\Appointment\Http\Controllers\Tenant\Admin;

use App\Helpers\ResponseMessage;
use App\Helpers\SanitizeInput;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Appointment\Entities\AppointmentDay;
use Modules\Blog\Entities\BlogCategory;

class AppointmentDaysController extends Controller
{

    public function index(Request $request)
    {
        $default_lang = $request->lang ?? default_lang();
        $all_days = AppointmentDay::select('id','day','key','status','created_at')->get();
        return view('appointment::tenant.backend.appointment-section.appointment-days',compact('all_days','default_lang'));
    }

    public function store(Request $request)
    {
        $request->validate(['day'=> 'required|max:191']);

        if(AppointmentDay::count() >= 7){
            return response()->danger(ResponseMessage::delete('You can not add day rather than 7'));
        }

        $category = new AppointmentDay();
        $category->setTranslation('day',$request->lang, SanitizeInput::esc_html($request->day));
        $category->status = $request->status;
        $category->key = $request->key;
        $category->save();

        return response()->success(ResponseMessage::SettingsSaved());
    }


    public function update(Request $request)
    {
        $request->validate(['day'=> 'required|max:191']);

        $category =  AppointmentDay::findOrFail($request->id);
        $category->setTranslation('day',$request->lang, SanitizeInput::esc_html($request->day));
        $category->status = $request->status;
        $category->key = $request->key;
        $category->save();

        return response()->success(ResponseMessage::SettingsSaved());
    }

    public function destroy($id)
    {
        $category =  AppointmentDay::where('id',$id)->first();
        $category->delete();

        return response()->danger(ResponseMessage::delete());
    }

    public function bulk_action(Request $request){
        AppointmentDay::whereIn('id',$request->ids)->delete();
        return response()->json(['status' => 'ok']);
    }
}
