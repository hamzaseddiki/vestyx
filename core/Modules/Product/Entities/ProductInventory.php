<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductInventory extends Model
{
    use HasFactory;

    protected $fillable = ["product_id","sku","stock_count","sold_count"];

    public $timestamps = false;

    public function inventoryDetails(): HasMany
    {
        return $this->hasMany(ProductInventoryDetail::class,"product_inventory_id","id");
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    protected static function newFactory()
    {
        return \Modules\Product\Database\factories\ProductInventoryFactory::new();
    }
}
