<?php

namespace App\Stats;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class ProductsQuery extends Query implements SubQuery
{
    /**
     *  Base products list
     */
    public function __construct()
    {

        /** @var Product|Builder $productsQuery */
        $this->query = Product::with([
            'brand.values',
            'category',
            'values.attribute.group',
            'values.year',
        ]);

    }

    public function filter(RequestFilters $requestFilters): self
    {
        // Filter products by all request params
        $this->query->byBrands($requestFilters->brands);
        $this->query->byCategory($requestFilters->categoryId);
        $this->query->byYear($requestFilters->yearId);
        $this->query->byAttributes($requestFilters->attributes);

        return $this;
    }

    public function getQuery(): Builder
    {
        return $this->query;
    }

    public function sort(Sort $sort)
    {
        $sort->apply($this->query);
    }

    public function paginate(...$args): \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        return $this->query->paginate(...$args);
    }
}
