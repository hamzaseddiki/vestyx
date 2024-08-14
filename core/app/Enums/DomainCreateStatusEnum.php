<?php

namespace App\Enums;

class DomainCreateStatusEnum
{
    const FAILED = 0;
    const ACTIVE = 1;


    public static function getText($const)
    {
        if ($const == self::FAILED){
            return __('Failed');
        }elseif ($const == self::ACTIVE){
            return __('Active');
        }
    }
}
