<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Stancl\Tenancy\Contracts\SyncMaster;
use Stancl\Tenancy\Database\Concerns\CentralConnection;
use Stancl\Tenancy\Database\Concerns\ResourceSyncing;

class PaymentLogs extends Model implements SyncMaster
{
    use LogsActivity, ResourceSyncing,CentralConnection;

    protected $table = 'payment_logs';
    protected $fillable = ['email','name','package_name','package_price','package_gateway','package_id',
        'user_id','tenant_id','attachments','custom_fields','status','track','transaction_id','payment_status','start_date','expire_date','renew_status','is_renew',
        'trial_expire_date','manual_payment_attachment','theme','unique_key','coupon_id','coupon_discount','assign_status','theme_code'
    ];

    protected static $recordEvents = ['created','deleted'];
    protected $casts = [
        'expire_date' => 'datetime',
        'batch_uuid' => null
    ];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'package_name', 'package_price', 'user_id']);
    }
    public function package(){
        return $this->belongsTo(PricePlan::class,'package_id','id');
    }
    public function user(){
        return $this->belongsTo('App\Models\User','user_id','id');
    }
    public function tenant(){
        return $this->belongsTo(Tenant::class,'tenant_id','id');
    }

    public function price_plan(){
        return $this->hasMany('App\Models\PricePlan','package_id','id');
    }

    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(Tenant::class);
    }

    public function getTenantModelName(): string
    {
        return 'CustomDomain';
    }

    public function getGlobalIdentifierKeyName(): string
    {
        return 'unique_key';
        // TODO: Implement getGlobalIdentifierKeyName() method.
    }

    public function getGlobalIdentifierKey()
    {
        return $this->getAttribute($this->getGlobalIdentifierKeyName());
    }

    public function getCentralModelName(): string
    {
        return static::class;
        // TODO: Implement getCentralModelName() method.
    }

    public function getSyncedAttributeNames(): array
    {
        return ['unique_key'];
        // TODO: Implement getSyncedAttributeNames() method.
    }

    public function triggerSyncEvent()
    {
        return null;
        // TODO: Implement triggerSyncEvent() method.
    }
}
