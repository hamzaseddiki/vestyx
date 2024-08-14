<?php

namespace App\Http\Controllers\Landlord\Frontend;

use App\Events\TenantNotificationEvent;
use App\Http\Controllers\Controller;
use App\Models\CustomDomain;
use App\Models\SupportDepartment;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportTicketController extends Controller
{

    public function page(){
        $departments = SupportDepartment::where(['status' => 1])->get();
        return view('landlord.frontend.pages.support-ticket.support-ticket',compact('departments'));
    }

    public function store(Request $request){
        $this->validate($request,[
            'title' => 'required|string|max:191',
            'subject' => 'required|string|max:191',
            'priority' => 'required|string|max:191',
            'description' => 'required|string',
            'departments' => 'required|string',
        ],[
            'title.required' => __('title required'),
            'subject.required' =>  __('subject required'),
            'priority.required' =>  __('priority required'),
            'description.required' => __('description required'),
            'departments.required' => __('departments required'),
        ]);

        $ticket = SupportTicket::create([
            'title' => $request->title,
            'via' => $request->via,
            'operating_system' => null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'description' => $request->description,
            'subject' => $request->subject,
            'status' => 'open',
            'priority' => $request->priority,
            'user_id' => Auth::guard('web')->user()->id,
            'admin_id' => null,
            'department_id' => $request->departments
        ]);

        //Notification Event
            $event_data = ['id' =>  $ticket->id, 'title' =>  __('New Support ticket'), 'type' =>  'support_ticket'];
            event(new TenantNotificationEvent($event_data));
        //Notification Event


        $msg = get_static_option('support_ticket_'.get_user_lang().'_success_message') ?? __('thanks for contact us, we will reply soon');
        return redirect()->back()->with(['msg' => $msg, 'type' => 'success']);
    }

}
