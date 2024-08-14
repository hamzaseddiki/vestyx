<?php

namespace Modules\Appointment\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubAppointmentComment extends Model
{
    protected $fillable = ['sub_appointment_id','user_id','commented_by','comment_content','status','parent_id'];
    protected $table = 'sub_appointment_comments';

    public function sub_appointment(){
        return $this->belongsTo(SubAppointment::class,'sub_appointment_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
