<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PlaceOrderTenant extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    public $attachment;
    public $package;

    public function __construct($data,$attachment_list,$package)
    {
        $this->data = $data;
        $this->attachment = $attachment_list;
        $this->package = $package ?? '';
    }


    public function build()
    {

        $tenant_mail = get_static_option('site_global_email');

        $mail = $this->from($tenant_mail,get_static_option('site_'.get_default_language().'_title'))

            ->subject(__('Order For').' '. $this->package->package_name.' From '.get_static_option('site_'.get_default_language().'_title'))
                ->view('emails.tenant_order');
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
