<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Wallet\Entities\WalletHistory;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';
    protected $fillable = ['notification_id','title','type','status'];

    public function payment_log() : BelongsTo
    {
        return $this->belongsTo(PaymentLogs::class, 'notification_id','id');
    }

    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'notification_id','id');
    }

    public function support_ticket() : BelongsTo
    {
        return $this->belongsTo(SupportTicket::class, 'notification_id','id');
    }
    public function support_ticket_message() : BelongsTo
    {
        return $this->belongsTo(SupportTicketMessage::class, 'notification_id','id');
    }

    public function custom_domain() : BelongsTo
    {
        return $this->belongsTo(CustomDomain::class, 'notification_id','id');
    }

    public function newsletter() : BelongsTo
    {
        return $this->belongsTo(Newsletter::class, 'notification_id','id');
    }

    public function wallet() : BelongsTo
    {
        return $this->belongsTo(WalletHistory::class, 'notification_id','id');
    }
}
