<?php

namespace Modules\Event\Entities;

use App\Models\MetaInfo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\Translatable\HasTranslations;

class Event extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'title', 'slug', 'content', 'organizer', 'organizer_email', 'organizer_phone',
        'venue_location', 'cost', 'category_id', 'total_ticket', 'available_ticket', 'status', 'date', 'time', 'image'
    ];

    protected $translatable = ['title','content'];

    public function category() : BelongsTo
    {
        return $this->belongsTo(EventCategory::class);
    }

    public function metainfo() : MorphOne
    {
        return $this->morphOne(MetaInfo::class,'metainfoable');
    }

    public function comments() : HasMany
    {
        return $this->hasMany(EventComment::class,'event_id','id');
    }
}
