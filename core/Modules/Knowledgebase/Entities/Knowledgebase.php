<?php

namespace Modules\Knowledgebase\Entities;

use App\Models\MetaInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\Translatable\HasTranslations;

class Knowledgebase extends Model
{
    use HasFactory, HasTranslations;
    protected $fillable = ['category_id','title','slug','description','image','views','status','files'];
    protected $translatable = ['title','description'];

    public function category() : BelongsTo
    {
        return $this->belongsTo(KnowledgebaseCategory::class);
    }

    public function metainfo() : MorphOne
    {
        return $this->morphOne(MetaInfo::class,'metainfoable');
    }
}
