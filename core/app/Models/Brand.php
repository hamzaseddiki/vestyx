<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $with = ['getImage'];
    protected $table = 'brands';
    protected $fillable = ['url','image','status'];

    public function getImage(){
        return $this->hasOne(MediaUploader::class,'id','image')->select(['id', 'path', 'alt']);
    }
}
