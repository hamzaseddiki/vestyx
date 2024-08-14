<?php

namespace Modules\TwoFactorAuthentication\Entities;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LoginSecurity extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'google2fa_enable',
        'google2fa_secret',

    ];

    protected $casts = [
        'user_id' => 'integer',
        'google2fa_enable' => 'integer',
    ];


    public function user()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
}
