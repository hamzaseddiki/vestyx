<?php

namespace Modules\Appointment\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class AppointmentSubcategory extends Model
{
    use HasTranslations;

    protected $fillable = ['title','status','appointment_category_id'];
    protected $translatable = ['title'];

    public function appointment_category() : BelongsTo
    {
        return $this->belongsTo(AppointmentCategory::class,'appointment_category_id','id');
    }

}
