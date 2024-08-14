<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use PDF;

class DonationMail extends Mailable
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
        $donation_details = $this->data;

        $invoice_details = PDF::loadView('tenant.frontend.invoice.donation',compact('donation_details'));

        $mail_address = get_static_option('donation_alert_receiving_mail') ?? get_static_option('site_global_email');

        $mail = $this->from($mail_address, get_static_option('site_'.get_user_lang().'_title'))
                    ->subject($this->subject)
                    ->markdown('emails.donation-payment-success')
                    ->attachData($invoice_details->output(), "donation-invoice.pdf");

        return $mail;
    }
}
