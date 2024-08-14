<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Language extends Model
{
    protected $fillable = ['name','slug','direction','status','default'];

    protected $casts = [
        'direction' => 'integer',
        'status' => 'integer',
        'default' => 'integer',
    ];
}
