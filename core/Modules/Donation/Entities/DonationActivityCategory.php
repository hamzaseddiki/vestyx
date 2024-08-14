<?php

namespace Modules\Donation\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class DonationActivityCategory extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = ['title','slug','status'];
    protected $translatable = ['title'];
}
