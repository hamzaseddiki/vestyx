<?php

namespace Modules\SupportTicket\Http\Controllers\Tenant\Frontend;

use App\Events\SupportMessage;
use App\Helpers\LanguageHelper;
use App\Helpers\ResponseMessage;
use App\Models\Language;
use App\Models\SupportDepartment;
use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class FrontendSupportTicketController extends Controller
{

   private const BASE_PATH = 'supportticket::tenant.frontend.';

    public function frontend_support_ticket_page(){
        $all_departments = SupportDepartment::where(['status' => 1])->get();
        return view(self::BASE_PATH.'support-ticket')->with(['departments' => $all_departments]);
    }
    public function support_ticket_store(Request $request){
        $request->validate([
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
            'via' => 'admin',
            'operating_system' => null,
            'user_agent' => null,
            'description' => $request->description,
            'subject' => $request->subject,
            'status' => 'open',
            'priority' => $request->priority,
            'user_id' => Auth::guard('web')->id(),
            'department_id' => $request->departments,
            'admin_id' => Null
        ]);

        $msg =  __('New ticket created successfully');
        return response()->success(ResponseMessage::SettingsSaved($msg));
    }

}
