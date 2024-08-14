<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\CountryManage\Entities\Country;
use Modules\TwoFactorAuthentication\Entities\LoginSecurity;
use Modules\Wallet\Entities\Wallet;
use Modules\Wallet\Entities\WalletTenantList;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Stancl\Tenancy\Contracts\Syncable;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, LogsActivity;

    protected $fillable = [
        'name',
        'email',
        'password',
        'has_subdomain',
        'username',
        'email_verified',
        'email_verify_token',
        'mobile',
        'company',
        'address',
        'city',
        'state',
        'country',
        'image',
        'api_token_plan_text',
        'temp_password',
        'facebook_id',
        'google_id',
    ];

    protected static $recordEvents = ['updated','created','deleted'];


    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email','username','created_at','updated_at']);
        // Chain fluent methods for configuration options
    }


    public function tenant_info(): BelongsTo
    {
        return $this->belongsTo(Tenant::class,'id','user_id');
    }
    public function tenant_details(): HasMany
    {
        return $this->hasMany(Tenant::class,'user_id','id')->orderBy('id','desc');
    }

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'email_verified' => 'integer',
    ];

    public function payment_log(): HasMany
    {
        return $this->hasMany(PaymentLogs::class,'user_id','id')->orderBy('id','desc');
    }

    public function payment_single_log(): HasOne
    {
        return $this->hasOne(PaymentLogs::class, 'user_id', 'id');
    }



    public function domains()
    {
        return $this->hasMany(UserDomain::class, 'tenant_id', 'id');
    }

    public function country_name() :BelongsTo
    {
        return $this->belongsTo(Country::class, 'country','id');
    }


    public function wallet_tenant_list(): HasMany
    {
        return $this->hasMany(WalletTenantList::class, 'user_id', 'id');
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class, 'user_id', 'id');
    }

    public function loginSecurity()
    {
        return $this->hasOne(LoginSecurity::class);
    }

}
