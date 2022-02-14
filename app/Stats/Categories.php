<?php

namespace App\Stats;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;

class Categories
{
    private ?array $brands;
    private ?int $yearId;
    private ?array $attributes;

    function __construct(array $brands = null, int $yearId = null, array $attributes = null)
    {
        $this->brands = $brands;
        $this->yearId = $yearId;
        $this->attributes = $attributes;
    }

    public function get() {
        return Category::query()
            ->whereHas('products', function (Builder $query) {
                /** @var Product $query */
                $query->byBrands($this->brands);
                $query->byYear($this->yearId);
                $query->byAttributes($this->attributes);

                // Do not filter categories by selected category
            })
            ->get();
    }
}
