<?php

namespace App\Jobs;

use App\Mail\PlaceOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class PlaceOrderMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $details;

    public function __construct($details)
    {
        $this->details = $details;
    }


    public function handle()
    {
        Mail::to($this->details['order_mail'])->send(new PlaceOrder($this->details['all_fields'], $this->details['all_attachment'], $this->details['package_details'],"admin"));
        Mail::to($this->details['package_details']->mail)->send(new PlaceOrder($this->details['all_fields'], $this->details['all_attachment'], $this->details['package_details'],'user'));

    }
}
