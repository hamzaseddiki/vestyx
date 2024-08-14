<?php

namespace Modules\Job\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class JobCategory extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['title','subtitle','image','status'];
    protected $translatable = ['title','subtitle'];
}
