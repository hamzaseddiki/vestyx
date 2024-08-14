<?php

namespace Modules\Job\Entities;

use App\Models\MetaInfo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\Translatable\HasTranslations;

class Job extends Model
{
    use HasFactory, HasTranslations;

    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'description',
        'experience',
        'designation',
        'employee_type',
        'working_days',
        'working_type',
        'job_location',
        'company_name',
        'salary_offer',
        'image',
        'deadline',
        'application_fee_status',
        'application_fee',
        'status'
    ];

    public $translatable = [
        'title',
        'description',
        'designation',
        'job_location',
        'company_name'
    ];

    public function metainfo() : MorphOne
    {
        return $this->morphOne(MetaInfo::class,'metainfoable');
    }

    public function category() : BelongsTo
    {
        return $this->belongsTo(JobCategory::class);
    }

}
