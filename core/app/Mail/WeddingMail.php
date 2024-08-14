<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use PDF;

class WeddingMail extends Mailable
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
        $wedding_details = $this->data;

        $invoice_details = PDF::loadView('tenant.frontend.invoice.wedding',compact('wedding_details'));

        $mail_address = get_static_option('site_global_email');

        $mail = $this->from($mail_address, get_static_option('site_'.get_user_lang().'_title'))
                    ->subject($this->subject)
                    ->markdown('emails.tenant.wedding-payment-success')
                    ->attachData($invoice_details->output(), "wedding-plan-invoice.pdf");

        return $mail;
    }
}
