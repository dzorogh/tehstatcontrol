<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * App\Models\Year
 *
 * @mixin \Eloquent
 * @mixin IdeHelperYear
 */
class Year extends Model
{
    use HasFactory;

    protected $table = 'stats_years';

    public function values(): HasMany
    {
        return $this->hasMany(AttributeValue::class, 'year_id');
    }
}
