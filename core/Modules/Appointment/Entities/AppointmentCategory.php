<?php

namespace Modules\Appointment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class AppointmentCategory extends Model
{
    use HasTranslations;

    protected $fillable = ['title','status'];
    protected $translatable = ['title'];

}
