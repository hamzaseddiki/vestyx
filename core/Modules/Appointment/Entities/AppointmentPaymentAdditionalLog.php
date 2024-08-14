<?php

namespace Modules\Appointment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppointmentPaymentAdditionalLog extends Model
{
    protected $fillable = ['appointment_payment_log_id','appointment_id','sub_appointment_id','appointment_price','sub_appointment_price'];

}
