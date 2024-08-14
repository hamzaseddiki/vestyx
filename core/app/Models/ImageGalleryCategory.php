<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class ImageGalleryCategory extends Model
{
    use HasFactory,HasTranslations;

    protected $table = 'image_gallery_categories';
    protected $fillable = ['title','status'];
    protected $translatable = array('title');
}
