<?php

namespace Modules\Attributes\Entities;

use App\Models\MediaUploader;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class ProductBrand extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $fillable = ["name","slug","description","title","image_id","banner_id", "url"];
    protected $translatable = ["name","description","title"];

    public function logo(): HasOne
    {
        return $this->hasOne(MediaUploader::class,"id","image_id");
    }

    public function banner(): HasOne
    {
        return $this->hasOne(MediaUploader::class,"id","banner_id");
    }



}
