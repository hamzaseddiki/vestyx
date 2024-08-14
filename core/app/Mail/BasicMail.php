<?php

namespace App\Mail;

use App\Facades\GlobalLanguage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BasicMail extends Mailable
{
    use Queueable, SerializesModels;
    public  $msg;
    public  $form_name;
    public  $attachment;
    public  $subject;
    public  $extra;
    public function __construct($msg,$subject,$form_name = null,$attachment = null, $extra = null)
    {

        $admin_mail_check = is_null(tenant()) ? get_static_option_central('site_global_email') : get_static_option('site_global_email');
        $this->msg = $msg;
        $this->form_name = !is_null($form_name) ? $form_name : $admin_mail_check;
        $this->attachment = $attachment;
        $this->extra = $extra;
        $this->subject = $subject;
    }

    public function build()
    {
        $mail = $this->from($this->form_name,get_static_option('site_'.GlobalLanguage::default_slug().'_title'))
            ->subject($this->subject)
            ->markdown('emails.basic');

        if (!is_null($this->attachment)){
            if (is_array($this->attachment)){
                $attachments = $this->attachment;
                foreach ($attachments as $field_name => $attached_file){
                    if (file_exists($attached_file)){
                        $mail->attach($attached_file);
                    }
                }
            }else{
                $mail->attach($this->attachment);
            }
        }
        return $mail;

    }
}
