<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanFeature extends Model
{
    use HasFactory;

    protected $table = 'plan_features';
    protected $fillable = ['plan_id','feature_name','status'];

    public function plan()
    {
        return $this->belongsTo(PricePlan::class,'plan_id','id');
    }
}
