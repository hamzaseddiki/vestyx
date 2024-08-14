<?php

namespace App\Jobs;

use App\Mail\BasicMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendAccountRemoveMailJob implements ShouldQueue
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
        $message['subject'] = 'Account will be removed -' . get_static_option('site_' . get_default_language() . '_title');
        $message['body'] = 'Your Account will be removed within ' . ($this->days_reaming) .  ' days.  Please subscribe to a plan before we remove your account'.'expire_date';
        $message['body'].= '<br><br><a href="'.route('landlord.frontend.plan.order',$this->paymentLog->id).'">'.__('Go to plan page').'</a>';
        Mail::to($this->paymentLog->email)->send(new BasicMail( $message['body'],$message['subject']));
    }
}
