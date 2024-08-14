<?php

namespace Modules\Appointment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppointmentTax extends Model
{
    use HasFactory;

    protected $fillable = ['appointment_id','tax_type','tax_amount'];


}
