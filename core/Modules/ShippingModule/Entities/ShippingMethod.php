<?php

namespace Modules\ShippingModule\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'zone_id',
        'is_default',
    ];

    protected $with = ['options'];

    public function options()
    {
        return $this->hasOne(ShippingMethodOption::class, 'shipping_method_id', 'id');
    }

    public function availableOptions()
    {
        return $this->hasOne(ShippingMethodOption::class, 'shipping_method_id', 'id')->where('status', 1);
    }

    public function zone()
    {
        return $this->belongsTo(Zone::class);
    }
}
