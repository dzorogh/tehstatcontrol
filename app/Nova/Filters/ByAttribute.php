<?php

namespace App\Nova\Filters;

use App\Models\Attribute;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class ByAttribute extends Filter
{
    public $name = 'Фильтр по аттрибуту';

    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    /**
     * Apply the filter to the given query.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        return $query->where('attribute_id', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function options(Request $request)
    {
        $attributes = Attribute::all();

        return $attributes->keyBy('title')->map(function ($attribute) {
            return $attribute->id;
        })->toArray();
    }
}
