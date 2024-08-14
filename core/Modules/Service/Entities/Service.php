<?php

namespace Modules\Service\Entities;

use App\Models\MetaInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class Service extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['category_id','slug','price_plan','meta_tag','meta_description','title','description','image','status'];
    private $translatable = ['title','description'];

    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id','id');
    }

    public function metainfo(){
        return $this->morphOne(MetaInfo::class,'metainfoable');
    }
}
