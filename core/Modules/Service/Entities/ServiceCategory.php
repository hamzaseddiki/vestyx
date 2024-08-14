<?php

namespace Modules\Service\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class ServiceCategory extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['title','status'];
    private $translatable = ['title'];

    protected static function newFactory()
    {
        return \Modules\Service\Database\factories\ServiceCategoryFactory::new();
    }
}
