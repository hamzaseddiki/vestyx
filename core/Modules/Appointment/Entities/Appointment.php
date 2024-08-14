<?php

namespace Modules\Appointment\Entities;

use App\Models\MetaInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Modules\Blog\Entities\BlogComment;
use Spatie\Translatable\HasTranslations;

class Appointment extends Model
{
    use HasTranslations;

    protected $fillable = ['appointment_category_id','appointment_subcategory_id','title','description','price','slug','status','is_popular','image','views','person','sub_appointment_status','tax_status','key'];
    protected $translatable = ['title','description'];

    public function metainfo(){
        return $this->morphOne(MetaInfo::class,'metainfoable');
    }

    public function additional_appointments(): HasMany
    {
        return $this->hasMany(AdditionalAppointment::class,'appointment_id','id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(AppointmentCategory::class,'appointment_category_id','id');
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(AppointmentSubcategory::class,'appointment_subcategory_id','id');
    }

    public function comments(){
        return $this->hasMany(AppointmentComment::class,'appointment_id','id');
    }

    public function sub_appointments(): HasManyThrough
    {
        return $this->hasManyThrough(SubAppointment::class, AdditionalAppointment::class,'appointment_id','id','id','sub_appointment_id');
    }

}
