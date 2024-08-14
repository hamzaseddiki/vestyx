<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;
use Stancl\Tenancy\Contracts\SyncMaster;
use Stancl\Tenancy\Database\Concerns\CentralConnection;
use Stancl\Tenancy\Database\Concerns\ResourceSyncing;

class WeddingPricePlan extends Model
{
    use HasFactory,HasTranslations;

    protected $fillable = ['title','features','price','is_popular','status','not_available_features'];
    public $translatable = ['title','features','not_available_features'];


}
