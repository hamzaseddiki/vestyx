<?php

namespace Modules\Attributes\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Product\Entities\ProductInventoryDetail;
use Modules\Product\Entities\ProductSize;
use Spatie\Translatable\HasTranslations;

class Size extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ["name","size_code","slug"];
    protected $translatable = ["name"];

    public function product_sizes(): HasMany
    {
        return $this->hasMany(ProductInventoryDetail::class, 'size', 'id');
    }

    protected static function newFactory()
    {
        return \Modules\Attributes\Database\factories\SizeFactory::new();
    }
}
