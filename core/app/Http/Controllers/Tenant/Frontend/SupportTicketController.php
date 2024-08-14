<?php

namespace App\Http\Controllers\Tenant\Frontend;

use App\Http\Controllers\Controller;
use App\Models\SupportDepartment;
use App\Models\SupportTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportTicketController extends Controller
{

    public function page(){
        $departments = SupportDepartment::where(['status' => 1])->get();
        return view('tenant.frontend.pages.support-ticket.support-ticket',compact('departments'));
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

        SupportTicket::create([
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
            'departments' => $request->departments
        ]);

        $msg = get_static_option('support_ticket_'.get_user_lang().'_success_message') ?? __('thanks for contact us, we will reply soon');
        return redirect()->back()->with(['msg' => $msg, 'type' => 'success']);
    }

}
