<?php

namespace Modules\Campaign\Entities;

use App\Models\Admin;
use App\Models\MediaUploader;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\Translatable\HasTranslations;

/**
 * App\Campaign\Campaign
 *
 * @property int $id
 * @property string $title
 * @property string $subtitle
 * @property int $image
 * @property string|null $start_date
 * @property string|null $end_date
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Campaign\CampaignProduct[] $products
 * @property-read int|null $products_count
 * @method static \Illuminate\Database\Eloquent\Builder|Campaign newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Campaign newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Campaign query()
 * @method static \Illuminate\Database\Eloquent\Builder|Campaign whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Campaign whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Campaign whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Campaign whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Campaign whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Campaign whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Campaign whereSubtitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Campaign whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Campaign whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Campaign extends Model
{
    use HasFactory, HasTranslations;
    protected $fillable = [
        'title',
        'subtitle',
        'image',
        'status',
        'start_date',
        'end_date',
        'admin_id',
        'vendor_id',
        'type'
    ];

    protected $translatable = ['title','subtitle'];

    public function campaignImage(): HasOne
    {
        return $this->hasOne(MediaUploader::class,"id","image");
    }

    public function products(): HasMany
    {
        return $this->hasMany(CampaignProduct::class);
    }

    public function admin(): HasOne
    {
        return $this->hasOne(Admin::class,"id","admin_id");
    }

    public function scopeProfile($query){
        return $query->when($this->type == 'admin', function ($q){
            $q->with("admin");
        })->when($this->type == "vendor" , function ($q){
            $q->with("vendor");
        });
    }
}
