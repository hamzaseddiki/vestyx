<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Testimonial extends Model
{
    use HasFactory, HasTranslations;
    protected $table = 'testimonials';
    protected $fillable = ['name','designation','description','company','image','status'];
    protected $translatable = ['name','designation','description', 'company'];
}
