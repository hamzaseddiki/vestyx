<?php

namespace Modules\ShippingModule\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    protected $with = ['region'];

    public function region()
    {
        return $this->hasOne(ZoneRegion::class, 'zone_id', 'id');
    }
}
