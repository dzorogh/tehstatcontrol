<?php

namespace App\Nova\Filters;

use App\Models\Category;
use App\Models\Year;
use Illuminate\Http\Request;
use Laravel\Nova\Filters\Filter;

class ByYear extends Filter
{
    public $name = 'Фильтр по году';

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
        if ($value === 'null') {
            return $query->whereNull('year_id');
        } else {
            return $query->where('year_id', $value);
        }
    }

    /**
     * Get the filter's available options.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function options(Request $request)
    {
        return Year::all()
            ->keyBy('value')
            ->map(function ($item) {
                return $item->id;
            })
            ->put('Без года', 'null') // Search where year is null, used in apply method
            ->toArray();
    }
}
