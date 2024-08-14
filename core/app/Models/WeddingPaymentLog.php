<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Stancl\Tenancy\Contracts\SyncMaster;
use Stancl\Tenancy\Database\Concerns\CentralConnection;
use Stancl\Tenancy\Database\Concerns\ResourceSyncing;

class WeddingPaymentLog extends Model
{

    protected $table = 'wedding_payment_logs';
    protected $fillable = ['email','name','package_name','package_price','package_gateway','package_id',
        'user_id','status','track','transaction_id','payment_status', 'manual_payment_attachment'];
    public function package(){
        return $this->belongsTo(WeddingPricePlan::class,'package_id','id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

}
