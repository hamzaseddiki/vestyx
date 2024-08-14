<?php

namespace Modules\CountryManage\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\HotelBooking\Entities\Hotel;
use Modules\TaxModule\Entities\StateTax;

class State extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'country_id',
        'status',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function stateTax()
    {
        return $this->hasOne(StateTax::class);
    }

    protected static function newFactory()
    {
        return \Modules\CountryManage\Database\factories\StateFactory::new();
    }
    public function hotels()
    {
        return $this->hasMany(Hotel::class);
    }
}
