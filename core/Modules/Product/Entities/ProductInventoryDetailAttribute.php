<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductInventoryDetailAttribute extends Model
{
    use HasFactory;

    protected $fillable = ["product_id","inventory_details_id","attribute_name","attribute_value"];

    public function inventory_details(): BelongsTo
    {
        return $this->belongsTo(ProductInventoryDetail::class, "inventory_details_id", "id");
    }
}
