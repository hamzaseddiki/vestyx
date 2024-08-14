<?php

namespace Modules\Portfolio\Entities;

use App\Models\MetaInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class Portfolio extends Model
{
    use HasFactory, HasTranslations;

    protected $table = 'portfolios';
    protected $fillable = ['category_id','title','url','description','slug','image','image_gallery','client','design','typography','tags','file','download','status'];
    protected $translatable = ['title','description','client','design','typography'];

    public function metainfo(){
        return $this->morphOne(MetaInfo::class,'metainfoable');
    }

    public function category(){
        return $this->belongsTo(PortfolioCategory::class,'category_id','id');
    }

}
