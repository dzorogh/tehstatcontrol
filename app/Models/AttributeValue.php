<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

/**
 * App\Models\AttributeValue
 *
 * @mixin \Eloquent
 * @mixin IdeHelperAttributeValue
 */
class AttributeValue extends Model
{
    use HasFactory;

    protected $table = 'stats_attribute_values';
    protected $fillable = ['value', 'attribute_id', 'product_id', 'year_id'];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class, 'attribute_id');
    }

    public function year(): BelongsTo
    {
        return $this->belongsTo(Year::class, 'year_id');
    }

    public function scopeByYear($query, $yearId = null)
    {
        if ($yearId) {
            $query->where('year_id', $yearId);
            $query->orWhereNull('year_id');
        }
    }
}
