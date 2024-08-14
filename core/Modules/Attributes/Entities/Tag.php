<?php

namespace Modules\Attributes\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class Tag extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ["tag_text"];
    protected $translatable = ["tag_text"];
}
