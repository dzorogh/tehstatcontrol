<?php

namespace App\Nova\Filters;

use App\Models\Category;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class ByCategory extends Filter
{
    public $name = 'Фильтр по категории';

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
        return $query->where('category_id', $value);
    }

    /**
     * Get the filter's available options.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function options(Request $request)
    {
        return Category::all()->keyBy('title')->map(function ($category) {
            return $category->id;
        })->toArray();
    }
}
