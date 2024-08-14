<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactMessage extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    public $subject;
    public $attachment;

    public function __construct($data,$attachment_list,$subject)
    {
        $this->data = $data;
        $this->subject = $subject;
        $this->attachment = $attachment_list;
    }

    public function build()
    {
        $admin_mail_check = is_null(tenant()) ? get_static_option_central('site_global_email') : get_static_option('site_global_email');

        $mail = $this->from($admin_mail_check, get_static_option('site_'.get_default_language().'_title'))
        ->subject($this->subject)
        ->view('emails.contact');

        if (!empty($this->attachment)){
            foreach ($this->attachment as $field_name => $attached_file){
                if (file_exists($attached_file)){
                    $mail->attach($attached_file);
                }
            }
        }

        return $mail;
    }
}
