<?php

namespace App\Listeners;

use App\Mail\BasicMail;
use App\SupportTicket;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SupportSendMailToUser
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $ticket_info = $event->message;
        if ($ticket_info->notify === 'on' && $ticket_info->type === 'admin'){
            //subject
            $subject = __('your have a new message in ticket').' #'.$ticket_info->id;
            $ticket_details = \App\Models\SupportTicket::findOrFail($ticket_info->support_ticket_id);
            $user_email = $ticket_details->user->email ?? '';
            $message = '<p>'.__('Hello').'<br>';
            $message .= '</p>';
            $message .= 'you have a new message in ticket no'.' #'.$ticket_info->id.'. ';
            $message .= '<br><strong><a href="'.route('tenant.user.dashboard.support.ticket.view',$ticket_info->support_ticket_id).'" >'.__('check messages').'</a><strong>';
            if (!empty($user_email)){
                try {
                    Mail::to($user_email)->send(new BasicMail($message,$subject));
                }catch (\Exception $e){
                    //show eerror message
                }
            }
        }
    }
}
