<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Attributes\Entities\Unit;

class ProductUom extends Model
{
    use HasFactory;

    protected $fillable = ["product_id","unit_id","quantity"];

    protected $table = 'product_uom';

    public $timestamps = false;

    public function uom_details()
    {
        return $this->belongsTo(Unit::class, 'unit_id', 'id');
    }

    protected static function newFactory()
    {
        return \Modules\Product\Database\factories\ProductUomFactory::new();
    }
}
