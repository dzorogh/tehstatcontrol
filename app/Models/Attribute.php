<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Attribute
 *
 * @mixin \Eloquent
 * @mixin IdeHelperAttribute
 */
class Attribute extends Model
{
    use HasFactory;

    protected $table = 'stats_attributes';
    protected $fillable = ['title'];

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function values(): HasMany
    {
        return $this->hasMany(AttributeValue::class, 'attribute_id');
    }
}
