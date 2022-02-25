<?php

namespace App\Stats;

use App\Models\Brand;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class Brands
{
    private ?int $categoryId;
    private ?int $yearId;
    private ?array $attributes;

    function __construct(int $categoryId = null, int $yearId = null, array $attributes = null)
    {
        $this->categoryId = $categoryId;
        $this->yearId = $yearId;
        $this->attributes = $attributes;

    }

    public function get() {
        return Brand::query()
            ->whereHas('products', function (Builder $query) {
                /** @var Product $query */
                $query->byCategory($this->categoryId);
                $query->byYear($this->yearId);
                $query->byAttributes($this->attributes);

                // Do not filter brands by selected brand
            })
            ->orderBy('order')
            ->orderBy('title')
            ->get();
    }
}
