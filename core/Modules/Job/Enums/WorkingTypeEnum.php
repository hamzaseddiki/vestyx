<?php

namespace Modules\Job\Enums;

class WorkingTypeEnum
{
    const PART_TIME = 0;
    const FULL_TIME = 1;
    const PROJECT_BASE = 2;

    public static function getText($const)
    {
        if ($const == 0){
            return __('Part Time');
        }elseif ($const == 1){
            return __('Full Time');
        }elseif ($const == 2){
            return __('Project Base');
        }
    }
}
