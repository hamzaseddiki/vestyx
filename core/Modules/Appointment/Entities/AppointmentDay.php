<?php

namespace Modules\Appointment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class AppointmentDay extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['day', 'status', 'key'];
    protected $translatable = ['day'];

    public function times(): HasMany
    {
        return $this->hasMany(AppointmentSchedule::class, 'day_id', 'id');
    }

}
