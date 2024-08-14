<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Attributes\Entities\DeliveryOption;

class ProductDeliveryOption extends Model
{
    use HasFactory;

    protected $fillable = ["product_id","delivery_option_id"];

    public function delivery_option(): HasOne
    {
        return $this->hasOne(DeliveryOption::class);
    }

    protected static function newFactory()
    {
        return \Modules\Product\Database\factories\ProductDeliveryOptionFactory::new();
    }
}
