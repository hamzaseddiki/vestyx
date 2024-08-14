<?php

namespace App\Listeners;

use App\Events\TenantNotificationEvent;
use App\Events\TenantRegisterEvent;
use App\Models\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;

class TenantNotificationListener
{

    public function __construct()
    {
        //
    }

    public function handle(TenantNotificationEvent $event)
    {
        $event = $event->notification_info;

        Notification::create([
            'notification_id' => $event['id'],
            'title' => $event['title'],
            'type' => $event['type'],
        ]);
    }
}
