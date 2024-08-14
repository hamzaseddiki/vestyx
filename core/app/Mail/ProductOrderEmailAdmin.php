<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProductOrderEmailAdmin extends Mailable
{
    use Queueable, SerializesModels;
    public $data;

    public function __construct($data)
    {
        $this->data = $data ?? '';
    }


    public function build()
    {
        $tenant_mail = get_static_option('site_global_email');

        $mail = $this->from($tenant_mail,get_static_option('site_'.get_default_language().'_title'))
                ->subject(__('New order from') .get_static_option('site_'.get_default_language().'_title'))
                ->markdown('emails.product_order_admin');

        return $mail;

    }
}
