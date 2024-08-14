<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageHistory extends Model
{
    use HasFactory;

    public $fillable = ['tenant_domain','payment_log_id','trial_status','trial_qty','zero_price_status','zero_package_qty','user_id'];

}
