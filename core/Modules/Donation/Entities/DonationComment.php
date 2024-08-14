<?php

namespace Modules\Donation\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DonationComment extends Model
{
    use HasFactory;

    protected $fillable = ['donation_id','user_id','commented_by','comment_content',];
    protected $table = 'donation_comments';

    public function donation(){
        return $this->belongsTo(Donation::class,'donation_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }


}
