<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use PDF;

class AppointmentMail extends Mailable
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
        $appointment_details = $this->data;

        $invoice_details = PDF::loadView('tenant.frontend.invoice.appointment',compact('appointment_details'));
        $mail_address = get_static_option('site_global_email');

        $mail = $this->from($mail_address, get_static_option('site_'.get_user_lang().'_title'))
                    ->subject($this->subject)
                    ->markdown('emails.appointment-payment-success')
                    ->attachData($invoice_details->output(), "appointment-invoice.pdf");

        return $mail;
    }
}
