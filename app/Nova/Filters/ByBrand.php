<?php

namespace App\Nova\Filters;

use App\Models\Brand;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class ByBrand extends Filter
{
    public $name = 'Фильтр по бренду';

    /**
     * The filter's component.
     *
     * @var string
     */
    public $component = 'select-filter';

    /**
     * Apply the filter to the given query.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  mixed  $value
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply(Request $request, $query, $value)
    {
        return $query->where('brand_id', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return Brand::all()->keyBy('title')->map(function ($brand) {
            return $brand->id;
        })->toArray();
    }
}
