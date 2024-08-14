<?php

namespace Modules\Appointment\Entities;

use App\Models\MetaInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class SubAppointment extends Model
{
    use HasTranslations;

    protected $fillable = ['title','description','price','slug','status','is_popular','image','views','person'];
    protected $translatable = ['title','description'];

    public function metainfo(){
        return $this->morphOne(MetaInfo::class,'metainfoable');
    }

    public function sub_appointments(): HasMany
    {
        return $this->hasMany(AdditionalAppointment::class,'sub_appointment_id','id');
    }

    public function comments(){
        return $this->hasMany(SubAppointmentComment::class,'sub_appointment_id','id');
    }

}
