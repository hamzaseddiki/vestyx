<?php

namespace App\Enums;

class DonationPaymentStatusEnum
{
    const PENDING = 0;
    const COMPLETE = 1;


    public static function getText(int $const)
    {
        if ($const == 0){
            return __('Pending');
        }elseif ($const == 1){
            return __('Complete');
        }
    }
}
