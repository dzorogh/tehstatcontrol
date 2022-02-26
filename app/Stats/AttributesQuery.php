<?php

namespace App\Stats;

use App\Models\Attribute;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;


class AttributesQuery extends Query
{
    public function __construct()
    {
        $this->query = Attribute::query();
    }

    public function filter(RequestFilters $requestFilters)
    {
        $this->query
            ->whereHas('values', function (Builder $query) use ($requestFilters) {
                $query->whereHasMorph(
                    'attributable',
                    Product::class,
                    function (Builder $query) use ($requestFilters) {
                        /** @var Product $query */
                        $query->byBrands($requestFilters->brands);
                        $query->byCategory($requestFilters->categoryId);
                        $query->byYear($requestFilters->yearId);
                        $query->byAttributes($requestFilters->attributes);
                    });
            })
            ->where(function ($query) use ($requestFilters) {
                $query->where('group_id', $requestFilters->groupId);
                $query->orWhereNull('group_id');
            })
            ->orderBy('order');

        return $this;
    }
}
