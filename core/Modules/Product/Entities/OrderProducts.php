<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Campaign\Entities\CampaignProduct;
use Modules\ShippingModule\Entities\UserShippingAddress;

class OrderProducts extends Model
{
    use HasFactory;

    protected $table = 'order_products';

    protected $fillable = [
        'order_id', 'product_id', 'variant_id', 'quantity'
    ];

    public function campaign_product(): HasOne
    {
        return $this->hasOne(CampaignProduct::class, 'product_id', 'product_id');
    }
}
