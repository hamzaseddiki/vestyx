<?php

namespace Modules\Product\Entities;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductReviews extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 'user_id','rating', 'review_text'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
