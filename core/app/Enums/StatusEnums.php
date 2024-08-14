<?php

namespace App\Enums;

class StatusEnums
{
    const DRAFT = 0;
    const PUBLISH = 1;


    public static function getText(int $const)
    {
        if ($const == 0){
            return __('Draft');
        }elseif ($const == 1){
            return __('Publish');
        }
    }
}
