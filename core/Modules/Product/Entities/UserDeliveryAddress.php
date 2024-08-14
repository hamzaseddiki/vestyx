<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\CountryManage\Entities\Country;
use Modules\CountryManage\Entities\State;

class UserDeliveryAddress extends Model
{
    use HasFactory;

    protected $fillable = [
      'user_id', 'country_id', 'state_id', 'city', 'address', 'full_name', 'phone', 'email'
    ];

    public function state(): BelongsTo    {
        return $this->belongsTo(State::class);
    }

    public function country(): BelongsTo    {
        return $this->belongsTo(Country::class);
    }
}
