<?php

namespace App\Helpers\EmailHelpers;

class MarkupGenerator
{
    public static function paragraph($data){
        return sprintf('<p>%s</p>',$data);
    }
    public static function heading($data,$tag = 'h1'){
        return sprintf('<%$1s>%$2s</%$1s>',$tag,$data);
    }
    public static function button($data){
        return sprintf('<div class="btn-wrap"><button class="anchor-btn">%s</button></div>',$data);
    }
    public static function table($data){}

    public static function anchor($title,$url){
        return sprintf('<div class="btn-wrap"><a class="anchor-btn" href="%$1s">%$2s</a></div>',$url,$title);
    }
    public static function code($data){
        return sprintf('<code class="verify-code" >%s</code>',$data);
    }
}
