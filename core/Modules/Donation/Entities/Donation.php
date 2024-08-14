<?php

namespace Modules\Donation\Entities;

use App\Models\Admin;
use App\Models\MetaInfo;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Donation extends Model
{
    use HasFactory, HasTranslations;

    protected $table = 'donations';
    protected $fillable = ['title','description','excerpt','slug','category_id','status','created_by','creator_id',
        'amount','raised','faq','deadline','image','image_gallery','popular','views'
    ];

    public $translatable = ['title','description','excerpt'];

    public function category(){
        return $this->belongsTo(DonationCategory::class,'category_id','id');
    }

    public function metainfo(){
        return $this->morphOne(MetaInfo::class,'metainfoable');
    }

    public function creator()
    {
        $model = $this->attributes['created_by'] == 'admin' ? Admin::class : User::class;
        $foreign_key = 'creator_id';
        return $this->belongsTo($model,$foreign_key,'id');
    }

    public function comments(){
        return $this->hasMany(DonationComment::class,'donation_id','id');
    }
}
