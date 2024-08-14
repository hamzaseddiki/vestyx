<?php

namespace Modules\Donation\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DonationPaymentLog extends Model
{
    use HasFactory;
    protected $fillable = ['donation_id','user_id','transaction_id','name','email','amount','payment_gateway','track','manual_payment_attachment','status'];

    public function donation() : BelongsTo
    {
        return $this->belongsTo(Donation::class);
    }

}
