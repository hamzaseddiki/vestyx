<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class FaqCategory extends Model
{
    use HasFactory, HasTranslations;

    protected $table = 'faq_categories';
    protected $fillable = ['title','status'];
    public $translatable = ['title'];
}
