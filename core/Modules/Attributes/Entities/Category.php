<?php

namespace Modules\Attributes\Entities;

use App\Models\MediaUploader;
use App\Models\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductCategory;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    use HasFactory , SoftDeletes, HasTranslations;

    protected $table = 'categories';
    protected $fillable = ["name","slug","description","image_id","status_id"];
    protected $translatable = ['name'];

    public function image(): HasOne
    {
        return $this->hasOne(MediaUploader::class,"id","image_id");
    }

    public function status(): HasOne
    {
        return $this->hasOne(Status::class,"id","status_id");
    }

    public function product_categories(): HasMany
    {
        return $this->hasMany(ProductCategory::class);
    }

}
