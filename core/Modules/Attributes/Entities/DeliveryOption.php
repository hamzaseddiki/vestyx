<?php

namespace Modules\Attributes\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class DeliveryOption extends Model
{
    use HasFactory, SoftDeletes, HasTranslations;

    protected $fillable = ["icon","title","sub_title"];
    protected $translatable = ["title","sub_title"];

}
