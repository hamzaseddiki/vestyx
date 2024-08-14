<?php

namespace App\Mail;

use App\Helpers\LanguageHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PDF;

class TenantCredentialMail extends Mailable
{
    use Queueable, SerializesModels;
    public $credential_username;
    public $credential_password;

    public function __construct($credential_username,$credential_password)
    {
        $this->credential_username = $credential_username ?? '';
        $this->credential_password = $credential_password ?? '';
    }

    public function build()
    {

        $mail = $this->from(get_static_option('site_global_email'))
                 ->subject(__('Your credential') .' From '.get_static_option('site_'.get_user_lang().'_title'))
                 ->markdown('emails.tenant_credential');

        return $mail;

    }
}
