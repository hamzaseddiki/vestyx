<?php

namespace Modules\Knowledgebase\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class KnowledgebaseCategory extends Model
{
    use HasFactory, HasTranslations;
    protected $fillable = ['title','image','status'];
    protected $translatable = ['title'];


    public function knowledgebase(){
        return $this->hasMany(Knowledgebase::class,"category_id","id");
    }

}
