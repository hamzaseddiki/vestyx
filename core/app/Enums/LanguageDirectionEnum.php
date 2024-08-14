<?php

namespace App\Enums;

class LanguageDirectionEnum
{
    const LTR = 'ltr';
    const RTL = 'rtl';

    public static function getText(int $const)
    {
        if ($const == 0){
            return __('LTR');
        }elseif ($const == 1){
            return __('RTL');
        }
    }
}
