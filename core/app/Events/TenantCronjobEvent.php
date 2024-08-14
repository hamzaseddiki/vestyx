<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Events\Dispatchable;

class TenantCronjobEvent
{
    use Dispatchable,Queueable;
    public $cronjob_info;

    public function __construct(array $cronjob_info)
    {
        $this->cronjob_info = $cronjob_info;
    }
}
