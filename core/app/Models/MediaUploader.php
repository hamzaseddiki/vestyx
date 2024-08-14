<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaUploader extends Model
{
    use HasFactory;
    protected $fillable = ['title','alt','size','path','dimensions','user_type','user_id'];

}
