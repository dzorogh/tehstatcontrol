<?php

namespace App\Stats;

use App\Models\Product;
use App\Models\Year;
use Illuminate\Database\Eloquent\Builder;

class YearsQuery extends Query implements SubQuery
{
    /**
     *  Get years, filter by products filtered by other params
     */
    function __construct()
    {
        $this->query = Year::query();
    }

    public function filter(RequestFilters $requestFilters): self
    {
        $this->query
            ->whereHas('values', function (Builder $query) use ($requestFilters) {
                $query->whereHasMorph('attributable', Product::class, function (Builder $query) use ($requestFilters) {
                    /** @var Product $query */
                    $query->byBrands($requestFilters->brands);
                    $query->byCategory($requestFilters->categoryId);
                    $query->byAttributes($requestFilters->attributes);

                    // Do not filter years by selected year
                });
            })
            ->orderBy('value', 'desc');

        return $this;
    }
}
