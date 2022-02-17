<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductsRequest;
use App\Http\Resources\Stats\AttributeResource;
use App\Http\Resources\Stats\GroupResource;
use App\Http\Resources\Stats\BrandResource;
use App\Http\Resources\Stats\ProductResource;
use App\Http\Resources\Stats\CategoryResource;
use App\Http\Resources\Stats\YearResource;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Group;
use App\Models\Product;
use App\Models\Category;
use App\Models\Year;
use App\Stats\Brands;
use App\Stats\Categories;
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
        $validated = $request->validated();

        $requestFilters = new RequestFilters();
        $requestFilters->setBrands($request->validated('filters.brandsIds'));
        $requestFilters->setCategory($request->validated('filters.categoryId'));
        $requestFilters->setAttributes($request->validated('filters.attributes'));
        $requestFilters->setYear($request->validated('filters.yearId', Year::query()->orderByDesc('value')->first()->id));

        if ($request->input('filters.groupSlug')) {
            $groupId = Group::whereSlug($request->validated('filters.groupSlug'))->first()->id;
        } elseif ($request->validated('filters.groupId')) {
            $groupId = $request->validated('filters.groupId');
        } else {
            $groupId = Group::query()->first()->id;
        }

        $requestFilters->setGroup($groupId);

        $sort = [
            'type' => $request->validated('sort.type', 'title'),
            'direction' => $request->validated('sort.direction', 'asc'),
            'attributeId' => $request->validated('sort.attributeId', null),
        ];

        // Base products list

        /** @var Product|Builder $productsQuery */
        $productsQuery = Product::with([
            'brand',
            'category',
            'values.attribute.group',
            'values.year'
        ]);

        // Filter products by all request params
        $productsQuery->byBrands($requestFilters->brands);
        $productsQuery->byCategory($requestFilters->categoryId);
        $productsQuery->byYear($requestFilters->yearId);
        $productsQuery->byAttributes($requestFilters->attributes);


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
                $query->whereHas('product', function (Builder $query) use ($requestFilters) {
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
            ->whereHas('product', function (Builder $query) use ($requestFilters) {
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
                    $query->orWhere(function ($query) use ($attribute, $requestFilters) {
                        $query->where('attribute_id', $attribute->id); // Maybe not necessary?

                        $query->whereHas('product', function ($query) use ($requestFilters, $attribute) {

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

        // List of visible columns
        $dynamicColumns = collect();

        if ($requestFilters->categoryId) {
            /** @var Category $category */
            $category = Category::query()->find($requestFilters->categoryId);

            if ($category) {
                $dynamicColumns = $dynamicColumns->merge($attributes->where('id', $category->main_attribute_id));
            }
        }

        if ($requestFilters->groupId) {
            $dynamicColumns = $dynamicColumns
                ->merge(
                    AttributeResource::collection(
                        $attributes->where('group_id', $requestFilters->groupId)
                    )
                );
        }

        // Stats by brand
        $chart = new StatsByBrand($productsQuery, $requestFilters->groupId, $requestFilters->yearId);
        $chart = $chart->get();

        $sorting = new Sort($productsQuery);
        $sorting->apply($sort['type'], $sort['direction'], $sort['attributeId']);

        return ProductResource::collection($productsQuery->paginate())->additional([
            'availableFilters' => [
                'brands' => BrandResource::collection($brands),
                'categories' => CategoryResource::collection($categories),
                'years' => YearResource::collection($years),
                'attributes' => AttributeResource::collection($attributes->where('data_type', '!=', 'comment')),
            ],
            // TODO: Remove not-actual filters from request filters???
            'requestFilters' => $requestFilters->all(),
            'dynamicColumns' => $dynamicColumns,
            'chart' => $chart,
            'sort' => $sort
        ]);
    }
}
