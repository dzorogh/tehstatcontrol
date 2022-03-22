<?php

namespace App\Models;

use App\Events\ProductDeleted;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Product
 *
 * @mixin \Eloquent
 * @mixin IdeHelperProduct
 */
class Product extends Model
{
    use HasFactory;

    protected $table = 'stats_products';
    protected $fillable = ['title', 'brand_id', 'category_id'];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function values()
    {
        return $this->morphMany(AttributeValue::class, 'attributable');
    }

    public function attributeValues()
    {
        return $this->morphMany(AttributeValue::class, 'attributable');
    }

    public function scopeByBrands($query, array $brands = null)
    {
        if ($brands && count($brands) > 0) {
            return $query->whereIn('brand_id', $brands);
        }
    }

    public function scopeByCategory($query, int $categoryId = null)
    {
        if ($categoryId) {
            return $query->where('category_id', $categoryId);
        }
    }

    public function scopeByYear($query, int $yearId = null)
    {
        if ($yearId) {
            $query->whereHas('values', function (Builder $query) use ($yearId) {
                /** @var AttributeValue $query */
                $query->byYear($yearId);
            });

            $query->with('values', function ($query) use ($yearId) {
                /** @var AttributeValue $query */
                $query->byYear($yearId);
            });
        }
    }

    public function scopeByAttributes($query, array $attributes = null)
    {
        if ($attributes && count($attributes) > 0) {
            foreach ($attributes as $attributeId => $attributeValues) {
                $query->whereHas('values', function (Builder $query) use ($attributeId, $attributeValues) {
                    $query->where('attribute_id', $attributeId);
                    $query->whereIn('value', $attributeValues);
                });
            }
        }
    }

    protected static function booted()
    {
        static::deleting(function (Product $product) {
            $product->values()->delete();
        });
    }
}
