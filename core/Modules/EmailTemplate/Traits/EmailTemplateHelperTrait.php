<?php

namespace Modules\EmailTemplate\Traits;

use App\Facades\GlobalLanguage;

trait EmailTemplateHelperTrait
{
    private function save_data($prefix,$request){

    foreach (GlobalLanguage::all_languages(1) as $lang){
        $this->validate($request,[
            $prefix.$lang->slug.'_subject',
            $prefix.$lang->slug.'_message',
        ]);
        $fields_list = [
            $prefix.$lang->slug.'_subject',
            $prefix.$lang->slug.'_message',
        ];
        foreach ($fields_list as $field){
            update_static_option($field,$request->$field);
        }
    }
}
}
