<?php

namespace App\Stats;

use App\Http\Requests\ProductsRequest;
use App\Models\AttributeValue;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class Sort
{
    private Builder $query;

    private string $type;
    private string $direction;
    private ?int $attributeId;
    private RequestFilters $requestFilters;

    function __construct(ProductsRequest $request, RequestFilters $requestFilters)
    {
        $this->type = $request->validated('sort.type', 'title');
        $this->direction = $request->validated('sort.direction', 'asc');
        $this->attributeId = $request->validated('sort.attributeId', null);

        $this->requestFilters = $requestFilters;
    }

    public function apply(Builder &$query)
    {
        $this->query = $query;

        if ($this->type === 'title') {
            $this->sortByTitle();
        }

        if ($this->type === 'brand') {
            $this->sortByBrand();
        }

        if ($this->type === 'category') {
            $this->sortByCategory();
        }

        if ($this->type === 'attribute') {
            $this->sortByAttribute();
        }
    }

    private function sortByTitle()
    {
        $this->query->orderBy('title', $this->direction);
    }

    private function sortByBrand()
    {
        $this->query->orderBy(
            Brand::select('title')
                ->whereColumn('stats_products.brand_id', 'stats_brands.id')
                ->limit(1),
            $this->direction);
    }


    private function sortByCategory()
    {
        $this->query->orderBy(
            Category::select('title')
                ->whereColumn('stats_products.category_id', 'stats_categories.id')
                ->limit(1),
            $this->direction);
    }

    /**
     * Sort by selected attribute
     * Double sort: (1) as string converted to number and then (2) as string
     *
     * @param string $direction
     * @param $attributeId
     */
    private function sortByAttribute()
    {
        $this->query->orderBy(
        // 1
            AttributeValue::select(DB::raw('value * 1'))
                ->where('attributable_type', 'product')
                ->whereColumn('stats_products.id', 'attributable_id')
                ->where('attribute_id', $this->attributeId)
                ->byYear($this->requestFilters->yearId)
                ->limit(1),
            $this->direction
        );

        $this->query->orderBy(
        // 2
            AttributeValue::select('value')
                ->where('attributable_type', 'product')
                ->whereColumn('stats_products.id', 'attributable_id')
                ->where('attribute_id', $this->attributeId)
                ->byYear($this->requestFilters->yearId)
                ->limit(1),
            $this->direction
        );
    }

    public function all() {
        return [
            'type' => $this->type,
            'direction' => $this->direction,
            'attributeId' => $this->attributeId
        ];
    }
}
