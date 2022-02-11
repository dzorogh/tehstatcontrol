<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * App\Models\Category
 *
 * @mixin \Eloquent
 * @mixin IdeHelperCategory
 */
class Category extends Model
{
    use HasFactory;

    protected $table = 'stats_categories';
    protected $fillable = ['title'];

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    public function main_attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class, 'main_attribute_id');
    }
}
