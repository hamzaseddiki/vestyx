<?php

namespace Modules\Event\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventPaymentLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id','user_id','name','email','phone','address',
        'transaction_id','ticket_qty','amount','payment_gateway',
        'track','manual_payment_attachment','status','note'
    ];

    public function event() : BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }


}
