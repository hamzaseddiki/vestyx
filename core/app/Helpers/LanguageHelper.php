<?php

namespace App\Helpers;

use App\Enums\StatusEnums;
use App\Models\Language;
use function session;

class LanguageHelper
{
    private static $language = null;
    private static $default = null;
    private static $user_lang_slug = null;
    private static $default_slug = null;
    private static $user_lang = null;
    private static $all_language = null;

    public function __construct()
    {
        self::lang_instance();
    }

    private  function lang_instance()
    {
        if (self::$language === null) {
            self::$language = new Language();
        }
        return self::$language;
    }

    public  function user_lang()
    {
        if (self::$user_lang === null) {
            $session_lang = session()->get('lang');
            if ( !empty($session_lang) && $session_lang !== self::default_slug()){
                self::$user_lang = self::lang_instance()->where('slug', session()->get('lang'))->first();
            }else{
                self::$user_lang = self::default();
            }
        }

        return self::$user_lang;
    }

    public  function default()
    {
        if (self::$default === null) {
            $default = self::lang_instance()->where('default', '1')->first();
            self::$default = $default;
        }
        return self::$default;
    }

    public  function default_slug()
    {
        if (self::$default_slug === null) {
            $default = self::lang_instance()->where('default', '1')->first() ;
            self::$default_slug = optional($default)->slug ?? config('app.locale');
        }
        return self::$default_slug;
    }
    public  function default_dir() : string
    {
        if (self::$default === null) {
            $default = self::lang_instance()->where('default', '1')->first() ?? 'ltr';
            self::$default = $default;
        }
        return optional(self::$default)->direction === 0 ? 'ltr' : 'rtl';
    }
    public  function user_lang_slug(){
        if (self::$user_lang_slug === null) {
            $default = self::lang_instance()->where('default', '1')->first();
            self::$user_lang_slug = session()->get('lang') ?? optional($default)->slug;
        }
        return self::$user_lang_slug;
    }
    public  function user_lang_dir() : string
    {
        return optional(self::user_lang())->direction === 0 ? 'ltr' : 'rtl';
//        return empty(optional(self::user_lang())->direction) ? 'ltr' : $result;
    }

    public function all_languages(int $type = 1)
    {
        if (self::$all_language === null && !is_null($type)) {
            self::$all_language = self::lang_instance()->where(['status' => $type])->get();
        }elseif(self::$all_language === null && is_null($type)){
            self::$all_language = self::lang_instance()->all();
        }
        return self::$all_language;
    }
}
