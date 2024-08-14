<?php

namespace App\Helpers;

class ResponseMessage
{
    public static function SettingsSaved($msg = null){
        return $msg ?? __('Settings Saved');
    }
    public static function mailSendSuccess(){
        return __('Mail Send Success');
    }
    public static function success($msg = null){
        return $msg ?? __('Save Success');
    }
    public static function delete($msg = null){
        return $msg ?? __('Delete Success');
    }
}
