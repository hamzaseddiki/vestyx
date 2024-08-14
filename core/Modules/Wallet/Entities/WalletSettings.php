<?php

namespace Modules\Wallet\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WalletSettings extends Model
{
    protected $fillable = ['user_id','renew_package','wallet_alert', 'minimum_amount'];

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
}
