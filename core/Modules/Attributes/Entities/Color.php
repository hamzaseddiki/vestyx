<?php

namespace Modules\Attributes\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Product\Entities\ProductInventoryDetail;
use Spatie\Translatable\HasTranslations;

class Color extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ["name","color_code","slug"];
    protected $translatable = ["name"];

    public function product_colors(): HasMany
    {
        return $this->hasMany(ProductInventoryDetail::class, 'color', 'id');
    }

    protected static function newFactory()
    {
        return \Modules\Attributes\Database\factories\ColorFactory::new();
    }
}
