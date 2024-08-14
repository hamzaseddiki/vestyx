<?php

namespace Modules\Badge\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class Badge extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $fillable = ['name', 'image', 'for', 'sale_count', 'type', 'status'];
    protected $table = 'badges';
    protected $translatable = ['name'];

    protected static function newFactory()
    {
        return \Modules\Badge\Database\factories\BadgeFactory::new();
    }
}
