<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Events\Dispatchable;

class TenantNotificationEvent
{
    use Dispatchable,Queueable;
    public $notification_info;

    public function __construct(array $notification_info)
    {
        $this->notification_info = $notification_info;
    }
}
