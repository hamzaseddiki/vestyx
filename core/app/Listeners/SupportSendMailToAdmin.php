<?php

namespace App\Listeners;

use App\Mail\BasicMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SupportSendMailToAdmin
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

    public function handle($event)
    {

        $ticket_info = $event->message;
        if ($ticket_info->notify === 'on' && $ticket_info->type == 'customer'){

            //subject
            $subject = __('your have a new message in ticket').' #'.$ticket_info->id;
            $admin_email = get_static_option('site_global_email') ?? '';

            $message = '<p>'.__('Hello').'<br>';
            $message .= 'you have a new message in ticket no'.' #'.$ticket_info->id.'. ';
            $message .= '<a class="anchor-btn" href="'.route('tenant.admin.support.ticket.view',$ticket_info->support_ticket_id).'">'.__('check messages').'</a>';
            $message .= '</p>';
            if (!empty($admin_email)){
                try {
                    Mail::to($admin_email)->send(new BasicMail($message,$subject));
                }catch (\Exception $e){
                    //show error message
                }
            }
        }
    }
}
