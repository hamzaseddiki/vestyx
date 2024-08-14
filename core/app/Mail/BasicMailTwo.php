<?php

namespace App\Mail;

use App\Facades\GlobalLanguage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BasicMailTwo extends Mailable
{
    use Queueable, SerializesModels;
    public  $msg;
    public  $subject;
    public  $from_data;
    public function __construct($msg,$subject,$from_data)
    {
        $this->msg = $msg;
        $this->subject = $subject;
        $this->from_data = $from_data;
    }

    public function build()
    {

        $mail = $this->from($this->from_data,get_static_option('site_'.GlobalLanguage::default_slug().'_title'))
            ->subject($this->subject)
            ->markdown('emails.basic-two');

        return $mail;

    }
}
