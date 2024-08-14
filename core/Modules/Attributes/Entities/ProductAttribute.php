<?php

namespace Modules\Attributes\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Product\ProductAttribute
 *
 * @property int $id
 * @property string|null $title
 * @property string|null $terms
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute query()
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereTerms($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ProductAttribute whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ProductAttribute extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'terms'];
}
