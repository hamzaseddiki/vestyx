<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class ImageGallery extends Model
{
    use HasFactory, HasTranslations;

    protected $table = 'image_galleries';
    protected $fillable = ['title','subtitle','status','image','category_id'];
    protected $translatable = ['title','subtitle'];

    public function category() :BelongsTo
    {
        return $this->belongsTo(ImageGalleryCategory::class, 'category_id','id');
    }
}
