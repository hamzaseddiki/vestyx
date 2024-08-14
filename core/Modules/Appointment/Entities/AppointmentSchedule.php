<?php

namespace Modules\Appointment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppointmentSchedule extends Model
{
    use HasFactory;

    protected $table = 'appointment_schedules';
    protected $fillable = ['day_id','allow_multiple','status','time','day_type'];

    public function day() : BelongsTo
    {
        return $this->belongsTo(AppointmentDay::class,'day_id','id');
    }

    public function type() : BelongsTo
    {
        return $this->belongsTo(AppointmentDayType::class,'day_type','id');
    }

}
