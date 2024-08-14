<?php

namespace App\Enums;

class AppointmentEnums
{
    const SINGLE = 1;
    const DOUBLE = 2;
    const TRIPLE = 3;


    public static function getText(int $const)
    {
        if ($const == self::SINGLE){
            return __('Single');
        }elseif ($const == self::DOUBLE){
            return __('Double');
        }elseif ($const == self::TRIPLE){
            return __('Triple');
        }else{
            return $const;
        }
    }
}
