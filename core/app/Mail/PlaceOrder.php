<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PDF;

class PlaceOrder extends Mailable
{
    use Queueable,SerializesModels;
    public $data;
    public $attachment;
    public $package;
    public $user_type;
    public $extra;

    public function __construct($data,$attachment_list,$package,$user_type, $extra)
    {
        $this->data = $data;
        $this->attachment = $attachment_list;
        $this->package = $package ?? '';
        $this->user_type = $user_type ?? '';
        $this->extra = $extra ?? '';
    }

    public function build()
    {


        $payment_details = $this->package;
        $invoice_details = PDF::loadView('landlord.frontend.invoice.package-order',compact('payment_details'));

        $invoice_details->setPaper('L');
        $invoice_details->output();
        $canvas = $invoice_details->getDomPDF()->getCanvas();

        $height = $canvas->get_height();
        $width = $canvas->get_width();

        $canvas->set_opacity(.2,"Multiply");
        $canvas->set_opacity(.2);
        $canvas->page_text($width/5, $height/2, __('Paid'), null, 55, array(0,0,0),2,2,-30);


        $mail = $this->from(get_static_option('site_global_email'), get_static_option('site_'.get_default_language().'_title'))
                 ->subject(__('Order For').' '. $this->package->package_name  .' From '.get_static_option('site_'.get_default_language().'_title'))
                 ->markdown('emails.order')
                   ->attachData($invoice_details->output(), "invoice.pdf");


        return $mail;

    }
}
