<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriberMessage extends Mailable
{
    use Queueable, SerializesModels;
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        $admin_mail_check = is_null(tenant()) ? get_static_option_central('site_global_email') : get_static_option('site_global_email');

        return $this->from($admin_mail_check, get_static_option('site_'.get_user_lang().'_title'))
            ->subject($this->data['subject'])
            ->view('emails.subscriber');
    }
}
