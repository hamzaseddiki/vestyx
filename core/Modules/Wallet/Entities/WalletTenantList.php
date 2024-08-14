<?php

namespace Modules\Wallet\Entities;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class WalletTenantList extends Model
{
    protected $fillable = ['user_id','tenant_id'];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id', 'id');
    }

    protected static function newFactory()
    {
        return \Modules\Wallet\Database\factories\WalletFactory::new();
    }
}
