<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTicketMessage extends Model
{
    protected $table = 'support_ticket_messages';
    protected $fillable = ['message','notify','attachment','support_ticket_id','type','user_id'];

    public function user_info(){
        if ($this->attributes['type'] === 'admin' && !is_null($this->attributes['user_id'])){
            return Admin::find($this->attributes['user_id']);
        }
        return User::find($this->attributes['user_id']);
    }
}
