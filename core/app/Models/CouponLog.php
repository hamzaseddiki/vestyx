<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponLog extends Model
{
    use HasFactory;

    protected $table = 'coupon_logs';
    protected $fillable = ['log_id','coupon_id','user_id'];
}
