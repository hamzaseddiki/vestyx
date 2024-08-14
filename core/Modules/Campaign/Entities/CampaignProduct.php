<?php

namespace Modules\Campaign\Entities;

use Modules\Product\Entities\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Campaign\CampaignProduct
 *
 * @property int $id
 * @property int $product_id
 * @property int $campaign_id
 * @property string $campaign_price
 * @property int $units_for_sale
 * @property string|null $start_date
 * @property string|null $end_date
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Campaign\Campaign|null $campaign
 * @property-read Product|null $product
 * @method static \Illuminate\Database\Eloquent\Builder|CampaignProduct newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CampaignProduct newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|CampaignProduct query()
 * @method static \Illuminate\Database\Eloquent\Builder|CampaignProduct whereCampaignId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CampaignProduct whereCampaignPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CampaignProduct whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CampaignProduct whereEndDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CampaignProduct whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CampaignProduct whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CampaignProduct whereStartDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CampaignProduct whereUnitsForSale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|CampaignProduct whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class CampaignProduct extends Model
{
    use HasFactory;
    protected $fillable = [
        'campaign_id',
        'product_id',
        'campaign_price',
        'units_for_sale',
        'start_date',
        'end_date',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function campaign()
    {
        return $this->belongsTo(Campaign::class, 'campaign_id', 'id');
    }

    public function sold_product()
    {
        return $this->belongsTo(CampaignProduct::class, 'product_id', 'product_id');
    }
}
