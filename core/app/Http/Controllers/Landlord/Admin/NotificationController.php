<?php

namespace App\Http\Controllers\Landlord\Admin;

use App\Helpers\ResponseMessage;
use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{

    public function index()
    {
        $all_notifications_date = Notification::orderByDesc('id')->get();
        return view('landlord.admin.notification.index',compact('all_notifications_date'));
    }

    public function view($id)
    {
       if(!empty($id)){
           $notification = Notification::find($id);
           $notification->status = $notification->status == 0 ? 1 :  1;
           $notification->save();
       }
        return view('landlord.admin.notification.view',compact('notification'));
    }

    public function delete(Request $request,$id){
        Notification::findOrFail($id)->delete();
        return response()->danger(ResponseMessage::delete());
    }

    public function bulk_action(Request $request){
        Notification::whereIn('id',$request->ids)->delete();
        return response()->json(['msg' => 'ok','type' => 'success']);
    }
}
