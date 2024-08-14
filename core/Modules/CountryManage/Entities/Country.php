<?php

namespace Modules\CountryManage\Entities;

use Modules\CountryManage\Entities\State;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\TaxModule\Entities\CountryTax;

class Country extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'status',
    ];

    public function states()
    {
        return $this->hasMany(State::class);
    }

    public function countryTax()
    {
        return $this->hasOne(CountryTax::class);
    }

}
