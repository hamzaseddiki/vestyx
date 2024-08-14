<?php

namespace App\Listeners;

use App\Events\TenantCronjobEvent;
use App\Events\TenantNotificationEvent;
use App\Events\TenantRegisterEvent;
use App\Models\CronjobLog;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class TenantCronjobListener
{

    public function __construct()
    {
        //
    }

    public function handle(TenantCronjobEvent $event)
    {
        $event = $event->cronjob_info;

        CronjobLog::create([
            'cronjob_id' => $event['id'],
            'title' => $event['title'],
            'type' => $event['type'],
            'running_qty' => $event['running_qty'],
        ]);
    }
}
