<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Brand
 *
 * @mixin \Eloquent
 * @mixin IdeHelperBrand
 */
class Brand extends Model
{
    use HasFactory;

    protected $table = 'stats_brands';
    protected $fillable = ['title'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'brand_id');
    }

    public function values()
    {
        return $this->morphMany(AttributeValue::class, 'attributable');
    }
}
