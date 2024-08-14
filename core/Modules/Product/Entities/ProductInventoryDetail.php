<?php

namespace Modules\Product\Entities;

use App\MediaUpload;
use App\Models\MediaUploader;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Attributes\Entities\Color;
use Modules\Attributes\Entities\Size;

class ProductInventoryDetail extends Model
{
    use HasFactory;

    protected $fillable = ["product_inventory_id","product_id","color","size","hash","additional_price","add_cost","image","stock_count","sold_count"];

    protected $with = ["attribute","attr_image"];

    public $timestamps = false;

    public function attribute(): HasMany
    {
        return $this->hasMany(ProductInventoryDetailAttribute::class, "inventory_details_id", "id");
    }

    public function attr_image(): HasOne
    {
        return $this->hasOne(MediaUploader::class,"id","image");
    }

    public function productColor()
    {
        return $this->hasOne(Color::class, 'id', 'color');
    }

    public function productSize()
    {
        return $this->hasOne(Size::class, 'id', 'size');
    }

    public function is_inventory_warn_able(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    protected static function newFactory()
    {
        return \Modules\Product\Database\factories\ProductInventoryDetailFactory::new();
    }
}
