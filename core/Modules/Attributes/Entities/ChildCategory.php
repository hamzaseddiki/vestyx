<?php

namespace Modules\Attributes\Entities;

use App\Models\MediaUploader;
use App\Models\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class ChildCategory extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $fillable = ["category_id","sub_category_id","name","slug","description","image_id","status_id"];
    protected $translatable = ['name'];

    public function category(): HasOne
    {
        return $this->hasOne(Category::class,"id","category_id");
    }

    public function sub_category(): HasOne
    {
        return $this->hasOne(SubCategory::class,"id","sub_category_id");
    }

    public function image(): HasOne
    {
        return $this->hasOne(MediaUploader::class,"id","image_id");
    }

    public function status(): HasOne
    {
        return $this->hasOne(Status::class,"id","status_id");
    }

    protected static function newFactory()
    {
        return \Modules\Attributes\Database\factories\ChildCategoryFactory::new();
    }
}
