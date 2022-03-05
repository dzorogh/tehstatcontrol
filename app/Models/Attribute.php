<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
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

    protected static function booted()
    {
        static::addGlobalScope('visible', function (Builder $builder) {
            $builder->where('is_hidden', false);
        });
    }

    public function scopeWithHidden() {
        return $this->withoutGlobalScope('visible');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    public function values(): HasMany
    {
        return $this->hasMany(AttributeValue::class, 'attribute_id');
    }

    public function category(): HasMany
    {
        return $this->hasMany(Category::class, 'main_attribute_id');
    }

    static function getAttributesByYear(): array
    {
        $attributes = Attribute::query()
            ->orderBy('order')
            ->get();

        $years = Year::query()
            ->orderBy('value')
            ->get();

        $result = [];

        foreach ($attributes as $attribute) {

            if ($attribute->by_year) {
                foreach ($years as $year) {
                    $result[] = [
                        'attribute_id' => $attribute->id,
                        'attribute' => $attribute,
                        'year_id' => $year->id,
                        'year' => $year,
                        'data_type' => $attribute->data_type
                    ];
                }
            } else {
                $result[] = [
                    'attribute_id' => $attribute->id,
                    'attribute' => $attribute,
                    'year_id' => null,
                    'year' => null,
                    'data_type' => $attribute->data_type
                ];
            }
        }

        return $result;
    }
}
