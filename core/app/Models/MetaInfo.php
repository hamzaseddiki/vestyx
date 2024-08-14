<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class MetaInfo extends Model
{
    use HasFactory,HasTranslations;

    protected $fillable = [
        'title',
        'description',
        'image',
        'fb_title',
        'fb_description',
        'fb_image',
        'tw_title',
        'tw_description',
        'tw_image',
        'metainfoable_id',
        'metainfoable_type'
    ];
    public $translatable = ['title','description'];

    public function metainfoable(){
        return $this->morphTo();
    }

}
