<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use PDF;

class StockOutEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {
        $stock_details = $this->data;
        $mail = $this->from(get_static_option('site_global_email'), get_static_option('site_'.get_default_language().'_title'))
                 ->subject(__('Product Stock Warning').' From '.get_static_option('site_'.get_default_language().'_title'))
                 ->view('emails.stock_out', compact('stock_details'));

        return $mail;
    }
}
