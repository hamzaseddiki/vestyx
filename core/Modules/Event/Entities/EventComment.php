<?php

namespace Modules\Event\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EventComment extends Model
{
    use HasFactory;

    protected $table = 'event_comments';
    protected $fillable = ['event_id','user_id','commented_by','comment_content',];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
