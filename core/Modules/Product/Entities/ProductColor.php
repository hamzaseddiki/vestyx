<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductColor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', // Red
        'color_code', // #F00
        'slug', // dark-red
    ];

    protected static function newFactory()
    {
        return \Modules\Product\Database\factories\ProductColorFactory::new();
    }
}
