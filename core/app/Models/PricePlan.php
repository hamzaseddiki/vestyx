<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;
use Stancl\Tenancy\Contracts\SyncMaster;
use Stancl\Tenancy\Database\Concerns\CentralConnection;
use Stancl\Tenancy\Database\Concerns\ResourceSyncing;

class PricePlan extends Model
{
    use HasFactory,HasTranslations;

    protected $fillable = ['title','subtitle','features','type','status','price','free_trial','page_permission_feature','blog_permission_feature','product_permission_feature','faq','portfolio_permission_feature','zero_price','storage_permission_feature','appointment_permission_feature'];
    public $translatable = ['title','subtitle','features'];

    protected $casts = [
        'type' => 'integer',
        'status' => 'integer'
    ];

    public function plan_features()
    {
        return $this->hasMany(PlanFeature::class,'plan_id','id');
    }

    public function plan_feature()
    {
        return $this->hasOne(PlanFeature::class,'plan_id','id')->orderBy('id','desc');
    }

}
