<?php

namespace App\Stats;

use App\Models\AttributeValue;
use App\Models\Brand;
use App\Models\Category;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class Sort
{
    private Builder $query;

    /**
     * @param Builder $query
     */
    function __construct(Builder &$query)
    {
        $this->query = $query;
    }

    /**
     * @param string $type 'title' | 'attribute' | 'brand' | 'category'
     * @param string $direction 'asc' | 'desc'
     * @param int|null $attributeId
     */
    public function apply(string $type, string $direction, int $attributeId = null)
    {
        if ($type === 'title') {
            $this->sortByTitle($direction);
        }

        if ($type === 'brand') {
            $this->sortByBrand($direction);
        }

        if ($type === 'category') {
            $this->sortByCategory($direction);
        }

        if ($type === 'attribute') {
            $this->sortByAttribute($direction, $attributeId);
        }
    }

    /**
     * @param string $direction
     */
    private function sortByTitle(string $direction)
    {
        $this->query->orderBy('title', $direction);
    }

    /**
     * @param string $direction
     */
    private function sortByBrand(string $direction)
    {
        $this->query->orderBy(
            Brand::select('title')
                ->whereColumn('stats_products.brand_id', 'stats_brands.id')
                ->limit(1),
            $direction);
    }

    /**
     * @param string $direction
     */
    private function sortByCategory(string $direction)
    {
        $this->query->orderBy(
            Category::select('title')
                ->whereColumn('stats_products.category_id', 'stats_categories.id')
                ->limit(1),
            $direction);
    }

    /**
     * Sort by selected attribute
     * Double sort: (1) as string converted to number and then (2) as string
     *
     * @param string $direction
     * @param $attributeId
     */
    private function sortByAttribute(string $direction, $attributeId)
    {
        $this->query->orderBy(
        // 1
            AttributeValue::select(DB::raw('value * 1'))
                ->whereColumn('stats_products.id', 'product_id')
                ->where('attribute_id', $attributeId)
                ->limit(1),
            $direction
        );

        $this->query->orderBy(
        // 2
            AttributeValue::select('value')
                ->whereColumn('stats_products.id', 'product_id')
                ->where('attribute_id', $attributeId)
                ->limit(1),
            $direction
        );
    }
}
