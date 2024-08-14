<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopbarInfo extends Model
{
    use HasFactory;

    protected $table ='topbar_infos';
    protected $fillable = ['icon','url'];
}
