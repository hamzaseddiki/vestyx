<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Sanctum\HasApiTokens;
use phpDocumentor\Reflection\Types\Integer;
use Stancl\Tenancy\Contracts\SyncMaster;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase,HasDomains;

    protected $casts = [
        'instruction_status' => 'integer'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function payment_log(): HasOne
    {
        return $this->hasOne(PaymentLogs::class, 'tenant_id', 'id');
    }

    public function payment_log_history(): HasOne
    {
        return $this->hasOne(PaymentLogHistory::class, 'tenant_id', 'id');
    }

    public function payment_log_for_sidebar(): HasOne
    {
        return $this->hasOne(PaymentLogs::class, 'tenant_id', 'id');
    }

    public function domain(): HasOne
    {
        return $this->hasOne(UserDomain::class, 'tenant_id', 'id');
    }

    public function issue(): HasOne
    {
        return $this->hasOne(TenantException::class, 'tenant_id', 'id');
    }

     public function custom_domain(): HasOne
    {
        return $this->hasOne(CustomDomain::class, 'old_domain', 'id');
    }


}
