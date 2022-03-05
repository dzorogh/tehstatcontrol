<?php

namespace App\Stats;

use App\Enums\AttributeDataType;
use App\Enums\RatingDirection;
use App\Http\Resources\AttributeResource;
use App\Models\Attribute;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\ArrayShape;

class StatsByBrand
{
    private int $groupId;
    private ?int $yearId;
    private Builder $productsQuery;

    public function __construct(Builder $productsQuery, int $groupId, int $yearId = null)
    {
        $this->groupId = $groupId;
        $this->yearId = $yearId;
        $this->productsQuery = clone $productsQuery;
    }

    public function get(): array
    {
        $groupAttributes = $this->getAttributes();

        $brandsStats = [];

        foreach ($groupAttributes as $groupAttribute) {
            $brandsStats[] = $this->getValues($groupAttribute);
        }

        return $brandsStats;
    }

    private function cloneQuery(): Builder
    {
        return clone $this->productsQuery;
    }

    private function makeQuery($query, $attributeId): Builder
    {
        $query->groupBy('stats_products.brand_id');

        $query->join('stats_attribute_values', 'stats_attribute_values.attributable_id', '=', 'stats_products.id')
            ->where('stats_attribute_values.attributable_type', '=', 'product');

        $query->where('attribute_id', $attributeId);

        if ($this->yearId) {
            $query->where(function ($query) {
                $query->where('year_id', $this->yearId);
                $query->orWhereNull('year_id');
            });
        }

        $query->with('brand');
        $query->select([
            DB::raw('avg(value) as avg'),
            'stats_products.brand_id'
        ]);

        return $query;
    }

    private function sortQuery($query, $ratingDirection)
    {
        if ($ratingDirection === RatingDirection::ASC) {
            $query->orderBy('avg', 'desc');
        } else {
            $query->orderBy('avg', 'asc');
        }

        return $query;
    }

    public function getAttributes(): \Illuminate\Database\Eloquent\Collection|array
    {
        return Attribute::whereGroupId($this->groupId)->where('show_on_chart', true)->orderBy('order')->orderBy('title')->get();
    }

    #[ArrayShape(['brands' => "array", 'values' => "array"])]
    public function getValues($groupAttribute): array
    {
        /** @var Builder|Product $query */
        $query = $this->cloneQuery();

        $query = $this->makeQuery($query, $groupAttribute->id);
        $query = $this->sortQuery($query, $groupAttribute->rating_direction);

        $productsStats = $query->get();

        $formatted = [
            'attribute' => new AttributeResource($groupAttribute),
            'brands' => [],
            'values' => []
        ];

        $productsStats->each(function ($item) use ($groupAttribute, &$formatted) {
            $value = $item->avg;
            $brand = $item->brand->title;

            $value = $this->formatDataType($groupAttribute, $value);

            $formatted['brands'][] = $brand;
            $formatted['values'][] = $value;
        });

        return $formatted;
    }

    private function formatDataType($attribute, $value)
    {
        if ($attribute->data_type === AttributeDataType::PERCENT) {
            $value = round($value, 3);
        }

        if ($attribute->data_type === AttributeDataType::RATING) {
            $value = round($value, 1);
        }

        if ($attribute->data_type === AttributeDataType::NUMBER) {
            $value = round($value, 1);
        }

        return $value;
    }
}
