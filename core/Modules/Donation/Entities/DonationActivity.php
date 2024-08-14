<?php

namespace Modules\Donation\Entities;

use App\Models\MetaInfo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class DonationActivity extends Model
{
    use HasFactory, HasTranslations;

    protected $table = 'donation_activities';
    protected $fillable = ['title','slug','description','image','category_id','status'];
    protected $translatable = ['title','description'];

    public function category() : BelongsTo
    {
        return $this->belongsTo(DonationActivityCategory::class, 'category_id','id');
    }

    public function metainfo(){
        return $this->morphOne(MetaInfo::class,'metainfoable');
    }
}
