<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class Faq extends Model
{
    use HasFactory, HasTranslations;

    protected $table = 'faqs';
    protected $fillable = ['title','description','status','category_id'];
    protected $translatable = ['title','description'];

    public function category()
    {
        return $this->belongsTo(FaqCategory::class,'category_id','id');
    }
}
