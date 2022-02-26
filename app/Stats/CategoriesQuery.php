<?php

namespace App\Stats;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class CategoriesQuery extends Query implements SubQuery
{
    /**
     * Get categories (product types) and filter by filtered products
     */
    function __construct()
    {
        $this->query = Category::query();
    }

    public function filter(RequestFilters $requestFilters): self
    {
        $this->query
            ->whereHas('products', function (Builder $query) use ($requestFilters) {
                /** @var Product $query */
                $query->byBrands($requestFilters->brands);
                $query->byYear($requestFilters->yearId);
                $query->byAttributes($requestFilters->attributes);

                // Do not filter categories by selected category
            })
            ->orderBy('order')
            ->orderBy('title');

        return $this;
    }
}
