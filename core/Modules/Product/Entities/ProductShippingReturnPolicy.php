<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class ProductShippingReturnPolicy extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ["product_id","shipping_return_description"];
    protected $translatable = ["shipping_return_description"];

    protected $table = 'product_shipping_return_policies';

}
