<?php

namespace Modules\User\Entities;

use App\MediaUpload;
use App\Shipping\UserShippingAddress;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $with = ["profile_image"];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'username',
        'password',
        'image',
        'phone',
        'address',
        'country',
        'state',
        'city',
        'zipcode',
        'email_verified',
        'email_verify_token',
        'facebook_id',
        'google_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function shipping()
    {
        return $this->hasMany(UserShippingAddress::class);
    }

    public function profile_image(){
        return $this->hasOne(MediaUpload::class,"id","image")->select("id","path","title","alt");
    }
}
