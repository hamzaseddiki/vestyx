<?php

namespace Modules\Portfolio\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Translatable\HasTranslations;

class PortfolioCategory extends Model
{
    use HasFactory, HasTranslations;
    protected $table = 'portfolio_categories';
    protected $fillable = ['id','title','status'];
    protected $translatable = ['title'];
}
