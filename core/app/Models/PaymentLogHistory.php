<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentLogHistory extends Model
{
    use HasFactory;

    protected $table = 'payment_log_histories';
    protected $fillable = ['email','name','package_name','package_price','package_gateway','package_id',
        'user_id','tenant_id','status','track','transaction_id','payment_status','start_date','expire_date','renew_status','is_renew',
        'manual_payment_attachment','theme','coupon_id','coupon_discount','assign_status'
    ];

    public function package(){
        return $this->belongsTo(PricePlan::class,'package_id','id');
    }
}
