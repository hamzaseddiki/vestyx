<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\CountryManage\Entities\Country;
use Modules\CountryManage\Entities\State;
use Modules\ShippingModule\Entities\UserShippingAddress;

class ProductOrder extends Model
{
    use HasFactory;

    protected $table = 'product_orders';

    protected $fillable = [
        'name',
        'email',
        'user_id', // buyer - nullable
        'country',
        'address',
        'city',
        'state',
        'zipcode',
        'phone',
        'message',

        'product_id',
        'coupon',
        'coupon_discounted',
        'total_amount',
        'status',

        'payment_status',
        'payment_gateway',
        'payment_track',
        'transaction_id',
        'checkout_image_path',
        'checkout_type',

        'order_details',
        'payment_meta',
        'shipping_address_id', // UserShippingAddress->id

        'selected_shipping_option'
    ];

    public function shipping()
    {
        return $this->hasOne(UserShippingAddress::class, 'id', 'shipping_address_id');
    }

    public function getCountry(): HasOne
    {
        return $this->hasOne(Country::class, 'id', 'country');
    }

    public function getState(): HasOne
    {
        return $this->hasOne(State::class, 'id', 'state');
    }

    public function sale_details()
    {
        return $this->hasMany(SaleDetails::class, 'order_id', 'id');
    }
}
