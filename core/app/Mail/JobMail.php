<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use PDF;

class JobMail extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    public $subject;
    public $type;


    public function __construct($data,$subject,$type)
    {
        $this->data = $data;
        $this->subject = $subject;
        $this->type = $type;
    }

    public function build()
    {
        $job_details = $this->data;
        $invoice_details = PDF::loadView('tenant.frontend.invoice.job',compact('job_details'));
        $mail_address = get_static_option('site_global_email');

        $mail = '';
        if(empty($job_details->amount)){
            $mail = $this->from($mail_address, get_static_option('site_'.get_user_lang().'_title'))
                ->subject($this->subject)
                ->markdown('emails.job-payment-success');
        }else{
            $mail = $this->from($mail_address, get_static_option('site_'.get_user_lang().'_title'))
                ->subject($this->subject)
                ->markdown('emails.job-payment-success')
                ->attachData($invoice_details->output(), "job-invoice.pdf");
        }

        return $mail;
    }
}
