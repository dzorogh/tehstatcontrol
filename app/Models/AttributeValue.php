<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
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
    protected $fillable = ['value', 'attribute_id', 'attributable_id', 'attributable_type', 'year_id'];

    public function attributable(): MorphTo
    {
        return $this->morphTo();
    }

/*    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'attributable_id');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class, 'attributable_id');
    }*/

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
