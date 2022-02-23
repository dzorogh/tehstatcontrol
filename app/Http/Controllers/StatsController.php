<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductsRequest;
use App\Http\Resources\AttributeResource;
use App\Http\Resources\GroupResource;
use App\Http\Resources\BrandResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\YearResource;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Group;
use App\Models\Product;
use App\Models\Category;
use App\Models\Year;
use App\Stats\Brands;
use App\Stats\Categories;
use App\Stats\ProductsQuery;
use App\Stats\RequestFilters;
use App\Stats\Sort;
use App\Stats\StatsByBrand;
use App\Stats\Years;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use JetBrains\PhpStorm\Pure;

class StatsController extends Controller
{
    public function groups(): AnonymousResourceCollection
    {
        return GroupResource::collection(Group::all());
    }

    #[Pure]
    public function group(Group $group): GroupResource
    {
        return new GroupResource($group);
    }

    public function products(ProductsRequest $request): AnonymousResourceCollection
    {

        $requestFilters = new RequestFilters($request);
        $requestSort = new Sort($request);

        // Base products list

        $products = new ProductsQuery();
        $products->filter($requestFilters);

        // Next - available params for each filter,
        // list of params must be filtered by products filtered by all other params except the param itself
        // Example: when filtered by year and brand, we must show only years available for products filtered by selected brand,
        // and only brands available for products filtered by selected year

        // Get brands and filter by products

        $brands = new Brands($requestFilters->categoryId, $requestFilters->yearId, $requestFilters->attributes);
        $brands = $brands->get();

        // Get categories (product types) and filter by filtered products

        $categories = new Categories($requestFilters->brands, $requestFilters->yearId, $requestFilters->attributes);
        $categories = $categories->get();

        // Get years, filter by products filtered by other params
        // Maybe we not need to filter at all, but within its better

        $years = new Years($requestFilters->categoryId, $requestFilters->brands, $requestFilters->attributes);
        $years = $years->get();

        // Get available attributes (not just values), we can filter it by all another params

        $attributes = Attribute::query()
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
            ->orderBy('order')
            ->get();

        // Only available options for attributes

        $attributesValuesQuery = AttributeValue::query()
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

        if ($requestFilters->attributes) {
            // When having attributes, we need to filter available attributes values only by other attribute values

            $attributesValuesQuery->where(function ($query) use ($attributes, $requestFilters) {
                // To make it we need get all available attributes before and for each of them get values

                foreach ($attributes as $attribute) {
                    $query->orWhere(function (Builder $query) use ($attribute, $requestFilters) {
                        $query->where('attribute_id', $attribute->id); // Maybe not necessary?

                        $query->whereHasMorph(
                            'attributable',
                            Product::class,
                            function ($query) use ($requestFilters, $attribute) {
                                // All attributes except current
                                /** @var Product $query */
                                $query->byAttributes(Arr::except($requestFilters->attributes, $attribute->id));
                            });
                    });
                }
            });
        }

        // Order attributes values by value, firstly as number, than as string
        $attributesValuesQuery
            ->groupBy('value', 'attribute_id')
            ->orderBy(DB::raw('value * 1'))
            ->orderBy('value');

        $attributesValues = $attributesValuesQuery->get();

        // Add related attributes values to attributes
        $attributes->each(function (Attribute $item) use ($attributesValues) {
            $item->setRelation('values', $attributesValues->where('attribute_id', $item->id));
        });

        // remove comment attributes and hidden
        $attributes = $attributes->where('data_type', '!=', 'comment')->where('show_filter', '=', true);

        // List of visible columns
        $dynamicColumns = collect();

        // Columns from category
        if ($requestFilters->categoryId) {
            /** @var Category $category */
            $category = Category::query()->find($requestFilters->categoryId);

            if ($category) {
                $dynamicColumns = $dynamicColumns->merge($attributes->where('id', $category->main_attribute_id));
            }
        }

        // Columns from group (all of them)
        if ($requestFilters->groupId) {
            $dynamicColumns = $dynamicColumns
                ->merge(Attribute::query()->where('group_id', $requestFilters->groupId)->get());
        }

        // Stats by brand
        $chart = new StatsByBrand($products->getQuery(), $requestFilters->groupId, $requestFilters->yearId);
        $chart = $chart->get();

        // Sort in the end, because chart data will be grouped without sorting columns
        $products->sort($requestSort);

        return ProductResource::collection($products->paginate())->additional([
            // incoming
            'requestFilters' => $requestFilters->all(),
            'requestSort' => $requestSort->all(),

            'availableFilters' => [
                'brands' => BrandResource::collection($brands),
                'categories' => CategoryResource::collection($categories),
                'years' => YearResource::collection($years),
                'attributes' => AttributeResource::collection($attributes),
            ],
            // TODO: Remove not-actual filters from request filters???

            'dynamicColumns' => AttributeResource::collection($dynamicColumns),
            'chart' => $chart,
        ]);
    }
}
