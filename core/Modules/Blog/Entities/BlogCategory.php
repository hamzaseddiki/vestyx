<?php

namespace Modules\Blog\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class BlogCategory extends Model
{
    use HasFactory, HasTranslations;
    protected $table = 'blog_categories';
    protected $fillable = ['title','status'];
    public $translatable = ['title'];

    public function blogs(){
        return $this->hasMany(BlogCategory::class);
    }

}
