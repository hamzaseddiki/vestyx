<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendPackageExpireEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public $days_reaming,public $paymentLog)
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $message['subject'] = 'Subscription Will Expire -' . get_static_option('site_' . get_user_lang() . '_title');
        $message['body'] = 'Your Subscription will expire very soon. Only ' . ($this->days_reaming).' Days Left. Please renew your subscription plan before expiration';
        try {
            Mail::to($this->paymentLog?->email)->send(new \App\Mail\BasicMail($message['body'],$message['subject']));
        }catch (\Exception $e){

        }
    }
}
