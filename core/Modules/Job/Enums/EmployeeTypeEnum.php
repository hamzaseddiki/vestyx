<?php

namespace Modules\Job\Enums;

class EmployeeTypeEnum
{
    const MALE = 0;
    const FEMALE = 1;
    const BOTH = 2;


    public static function getText($const)
    {
        if ($const == 0){
            return __('Male');
        }elseif ($const == 1){
            return __('Female');
        }elseif ($const == 2){
            return __('Male Female');
        }
    }
}
