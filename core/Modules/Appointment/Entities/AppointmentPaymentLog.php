<?php

namespace Modules\Appointment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class AppointmentPaymentLog extends Model
{

    protected $casts = [
      'appointment_date' => 'string'
    ];

    protected $fillable = [
        'user_id',
        'appointment_id',
        'name',
        'email',
        'phone',
        'appointment_date',
        'appointment_time',
        'appointment_price',
        'coupon_type',
        'coupon_code',
        'coupon_discount',
        'tax_amount',
        'subtotal',
        'total_amount',
        'payment_gateway',
        'status',
        'payment_status',
        'transaction_id',
        'manual_payment_attachment',
    ];

    public function appointment() :BelongsTo
    {
        return $this->belongsTo(Appointment::class,'appointment_id','id');
    }

    public function additional_appointment_logs(): HasMany
    {
        return $this->hasMany(AppointmentPaymentAdditionalLog::class,'appointment_payment_log_id','id');
    }

    public function sub_appointment_log_items(): HasManyThrough
    {
        return $this->hasManyThrough(SubAppointment::class, AppointmentPaymentAdditionalLog::class,'appointment_payment_log_id','id','id','sub_appointment_id');
    }


}
