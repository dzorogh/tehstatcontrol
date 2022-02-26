<?php

namespace App\Stats;

use App\Models\AttributeValue;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class AttributeValueQuery extends Query implements SubQuery
{
    /**
     * Get brands and filter by products
     *
     */
    function __construct()
    {
        $this->query = AttributeValue::query();
    }

    public function filter(RequestFilters $requestFilters): self
    {
        $this->query
            ->select('value', 'attribute_id')
            ->whereHasMorph(
                'attributable',
                Product::class,
                function (Builder $query) use ($requestFilters) {
                    /** @var Product $query */
                    $query->byBrands($requestFilters->brands);
                    $query->byCategory($requestFilters->categoryId);
                })
            ->where(function ($query) use ($requestFilters) {
                /** @var Product $query */
                $query->byYear($requestFilters->yearId);
            })
            ->whereHas('attribute', function (Builder $query) {
                $query->where('data_type', '!=', 'comment');
            });

        // Order attributes values by value, firstly as number, than as string
        $this->query
            ->groupBy('value', 'attribute_id')
            ->orderBy(DB::raw('value * 1'))
            ->orderBy('value');

        return $this;
    }
}
