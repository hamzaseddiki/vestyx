<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';
    protected $fillable = ['status','checkout_type','user_id','payment_status','custom_fields','attachment','package_name','package_price','package_id'];

    public function package(){
        return $this->hasOne('App\Models\PricePlan','id','package_id');
    }
    public function user(){
        return $this->hasOne('App\Models\User','id','user_id');
    }
    public function paymentlog(){
        return $this->hasOne('App\Models\PaymentLogs','order_id','id');
    }
}
