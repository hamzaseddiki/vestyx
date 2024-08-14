<?php

namespace Modules\Wallet\Entities;

use App\Models\Tenant;
use App\Observers\WalletBalanceObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Stancl\Tenancy\Contracts\SyncMaster;
use Stancl\Tenancy\Database\Concerns\CentralConnection;
use Stancl\Tenancy\Database\Concerns\ResourceSyncing;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','balance','status'];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class,'user_id','id');
    }

    public function walletSettings(): HasOne
    {
        return $this->hasOne(WalletSettings::class, 'user_id', 'user_id');
    }

    protected static function newFactory()
    {
        return \Modules\Wallet\Database\factories\WalletFactory::new();
    }
}
