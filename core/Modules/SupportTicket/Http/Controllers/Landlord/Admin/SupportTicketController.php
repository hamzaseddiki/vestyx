<?php

namespace Modules\SupportTicket\Http\Controllers\Landlord\Admin;

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

class SupportTicketController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:support-ticket-list|support-ticket-create|support-ticket-edit|support-ticket-delete',['only' => ['all_tickets','priority_change','status_change']]);
        $this->middleware('permission:support-ticket-create',['only' => ['new_ticket','store_ticket']]);
        $this->middleware('permission:support-ticket-edit',['only' => ['priority_change','status_change']]);
        $this->middleware('permission:support-ticket-delete',['only' => ['delete','bulk_action']]);
    }
   private const BASE_PATH = 'supportticket::landlord.admin.support-ticket.';

    public function all_tickets(){
        $all_tickets = SupportTicket::orderBy('id','desc')->get();
        return view(self::BASE_PATH .'all-tickets')->with(['all_tickets' => $all_tickets ]);
    }

    public function new_ticket(){
        $all_users = User::all();
        $all_departments = SupportDepartment::where(['status' => 1])->get();
        return view(self::BASE_PATH.'new-ticket')->with(['all_users' => $all_users,'departments' => $all_departments]);
    }
    public function store_ticket(Request $request){
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
            'user_id' => $request->user_id,
            'department_id' => $request->departments,
            'admin_id' => Auth::guard('admin')->user()->id
        ]);

        $msg =  __('new ticket created successfully');
        return response()->success(ResponseMessage::SettingsSaved($msg));
    }


    public function priority_change(Request $request){
        $request->validate([
            'priority' => 'required|string|max:191'
        ]);
        SupportTicket::findOrFail($request->id)->update([
            'priority' => $request->priority,
        ]);
        return response()->json('ok');
    }
    public function status_change(Request $request){
        $request->validate([
            'status' => 'required|string|max:191'
        ]);
        SupportTicket::findOrFail($request->id)->update([
            'status' => $request->status,
        ]);
        return response()->json('ok');
    }

    public function delete(Request $request,$id){
        SupportTicket::findOrFail($id)->delete();
        return response()->danger(ResponseMessage::delete('Service Deleted'));
    }

    public function view(Request $request,$id){
        $ticket_details = SupportTicket::findOrFail($id);
        $all_ticket_messages = SupportTicketMessage::where(['support_ticket_id'=>$id])->get();
        $q = $request->q ?? '';
        return view(self::BASE_PATH.'view-ticket')->with(['ticket_details' => $ticket_details,'all_ticket_messages' => $all_ticket_messages,'q' => $q]);
    }

    public function send_message(Request $request){

        $request->validate([
            'ticket_id' => 'required',
            'user_type' => 'required|string|max:191',
            'message' => 'required',
            'send_notify_mail' => 'nullable|string',
            'file' => 'nullable|mimes:zip',
        ]);

        $ticket_info = SupportTicketMessage::create([
            'support_ticket_id' => $request->ticket_id,
            'type' => $request->user_type,
            'user_id' => Auth::guard('admin')->user()->id,
            'message' => $request->message,
            'notify' => $request->send_notify_mail ? 'on' : 'off',
        ]);

        if ($request->hasFile('file')){
            $uploaded_file = $request->file;
            $file_extension = $uploaded_file->getClientOriginalExtension();
            $file_name =  pathinfo($uploaded_file->getClientOriginalName(),PATHINFO_FILENAME).time().'.'.$file_extension;
            $uploaded_file->move('assets/uploads/ticket',$file_name);
            $ticket_info->attachment = $file_name;
            $ticket_info->save();
        }

        //send mail to user
        event(new SupportMessage($ticket_info));

        return response()->success(ResponseMessage::SettingsSaved('Message Sent'));
    }

    public function bulk_action(Request $request){
        SupportTicket::whereIn('id',$request->ids)->delete();
        return response()->json('ok');
    }

    public function page_settings(){
        return view(self::BASE_PATH.'page-settings');
    }

    public function update_page_settings(Request $request){
        foreach (Language::all() as $lang){
            $request->validate([
                'support_ticket_'.$lang->slug.'_login_notice' => 'nullable|string',
                'support_ticket_'.$lang->slug.'_form_title' => 'nullable|string',
                'support_ticket_'.$lang->slug.'_button_text' => 'nullable|string',
                'support_ticket_'.$lang->slug.'_success_message' => 'nullable|string',
            ]);
            $field_list = [
                'support_ticket_'.$lang->slug.'_login_notice',
                'support_ticket_'.$lang->slug.'_form_title',
                'support_ticket_'.$lang->slug.'_button_text',
                'support_ticket_'.$lang->slug.'_success_message',
            ];
            foreach ($field_list as $field){
                update_static_option($field,$request->$field);
            }
        }
        return response()->success(ResponseMessage::SettingsSaved('Item Saved Succesfully..'));
    }
}
