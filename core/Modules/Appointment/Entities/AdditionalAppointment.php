<?php

namespace Modules\Appointment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AdditionalAppointment extends Model
{
    use HasFactory;

    protected $table = 'additional_appointments';
    protected $fillable = ['appointment_id','sub_appointment_id'];

}
