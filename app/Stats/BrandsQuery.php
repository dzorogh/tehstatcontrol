<?php

namespace App\Stats;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class BrandsQuery extends Query implements SubQuery
{
    /**
     * Get brands and filter by products
     *
     */
    function __construct()
    {
        $this->query = Brand::query();
    }

    public function filter(RequestFilters $requestFilters): self
    {
        $this->query
            ->whereHas('products', function (Builder $query) use ($requestFilters) {
                /** @var Product $query */
                $query->byCategory($requestFilters->categoryId);
                $query->byYear($requestFilters->yearId);
                $query->byAttributes($requestFilters->attributes);

                // Do not filter brands by selected brand
            })
            ->orderBy('order')
            ->orderBy('title');

        return $this;
    }
}
