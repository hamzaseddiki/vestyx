<?php

namespace Modules\Appointment\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Blog\Entities\Blog;

class AppointmentComment extends Model
{

    protected $fillable = ['appointment_id','user_id','commented_by','comment_content','status','parent_id'];
    protected $table = 'appointment_comments';

    public function appointment(){
        return $this->belongsTo(Appointment::class,'appointment_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
