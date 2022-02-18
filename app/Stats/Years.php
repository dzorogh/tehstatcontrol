<?php

namespace App\Stats;

use App\Models\Product;
use App\Models\Year;
use Illuminate\Database\Eloquent\Builder;

class Years
{

    private ?int $categoryId;
    private ?array $brands;
    private ?array $attributes;

    function __construct($categoryId, $brands, $attributes)
    {
        $this->categoryId = $categoryId;
        $this->brands = $brands;
        $this->attributes = $attributes;
    }

    public function get() {
        return Year::query()
            ->whereHas('values', function (Builder $query) {
                $query->where('attributable_type', 'product');
                $query->whereHas('attributable', function (Builder $query) {
                    /** @var Product $query */
                    $query->byBrands($this->brands);
                    $query->byCategory($this->categoryId);
                    $query->byAttributes($this->attributes);

                    // Do not filter years by selected year
                });
            })
            ->orderBy('value', 'desc')
            ->get();
    }
}
