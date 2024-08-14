<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Events\Dispatchable;

class TenantRegisterEvent
{
    use Dispatchable,Queueable;
    public $user_info;
    public $subdomain;
    public $theme;
    public function __construct(User $user, $subdomain, $theme = 'theme-1')
    {
        $this->user_info = $user;
        $this->subdomain = $subdomain;
        $this->theme = $theme;
    }
}
