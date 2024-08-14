<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class FormBuilder extends Model
{
    use HasFactory, HasTranslations;

    protected $table = 'form_builders';
    protected $fillable = ['title','email','button_text','fields','success_message'];
    protected $translatable = ['title','button_text','success_message'];
}
