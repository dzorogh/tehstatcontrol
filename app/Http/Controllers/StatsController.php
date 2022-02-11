<?php

namespace App\Http\Controllers;

use App\Http\Resources\Stats\AttributeResource;
use App\Http\Resources\Stats\AttributeValueResource;
use App\Http\Resources\Stats\GroupResource;
use App\Http\Resources\Stats\BrandResource;
use App\Http\Resources\Stats\ProductResource;
use App\Http\Resources\Stats\CategoryResource;
use App\Http\Resources\Stats\YearResource;
use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Group;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Year;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{
    public function groups(): AnonymousResourceCollection
    {
        return GroupResource::collection(Group::all());
    }

    public function group(Group $group): GroupResource
    {
        return new GroupResource($group);
    }

    public function products(Request $request): AnonymousResourceCollection
    {
        // ->where(function (Builder $query) use ($request) {
        //                $query->where('group_id', $request->input('group_id'));
        //                $query->orWhereNull('group_id');
        //            })

        // Creating filters (request params)

        if ($request->input('group_slug')) {
            $groupId = Group::whereSlug($request->input('group_slug'))->first()->id;
        } elseif ($request->input('group_id')) {
            $groupId = $request->input('group_id');
        } else {
            $groupId = Group::first();
        }

        $attributes = [];

        if ($request->input('attributes')) {
            foreach ($request->input('attributes') as $index => $attributeParams) {
                if ($request->input("attributes.${index}.id") && $request->input("attributes.${index}.value")) {
                    $attributes[$request->input("attributes.${index}.id")] = $request->input("attributes.${index}.value");
                }
            }
        }

        $requestParams = [
            // Brands is array, we can sort by multiply brands
            'brands' => $request->input('brands'),
            'category_id' => $request->input('category'),
            'year_id' => $request->input('year') ?? Year::query()->orderByDesc('value')->first()->id,
            'attributes' => $attributes,
            'group_id' => $groupId,
        ];

        // Base products list

        /** @var Product $productsQuery */
        $productsQuery = Product::with([
            'brand',
            'category',
            'values.attribute.group',
            'values.year'
        ]);

        // Filter products by all request params
        $productsQuery->byBrands($requestParams['brands']);
        $productsQuery->byCategory($requestParams['category_id']);
        $productsQuery->byYear($requestParams['year_id']);
        $productsQuery->byAttributes($requestParams['attributes']);

        // Sort by selected attribute
        // TODO: IT IS DEMO only for one param, make full sorting with other params!
        // TODO: Make available filters list, only for data shown in columns
        // Double sort: (1) by string converted to number and then (2) by string

        // TODO: MAKE SORTING
//        $productsQuery->orderByDesc(
//            AttributeValue::select(DB::raw('value * 1')) // 1
//                ->whereColumn('stats_products.id', 'product_id')
//                ->where('attribute_id', 3)
//                ->limit(1)
//        );
//
//        $productsQuery->orderByDesc(
//            AttributeValue::select('value') // 2
//                ->whereColumn('stats_products.id', 'product_id')
//                ->where('attribute_id', 3)
//                ->limit(1)
//        );

        // Next - available params for each filter,
        // list of params must be filtered by products filtered by all other params except the param itself
        // Example: when filtered by year and brand, we must show only years available for products filtered by selected brand,
        // and only brands available for products filtered by selected year

        // Get brands and filter by products

        $brands = Brand::query()
            ->whereHas('products', function (Builder $query) use ($requestParams) {
                /** @var Product $query */
                $query->byCategory($requestParams['category_id']);
                $query->byYear($requestParams['year_id']);
                $query->byAttributes($requestParams['attributes']);

                // Do not filter brands by selected brand
            })
            ->get();

        // Get categories (product types) and filter by filtered products

        $categories = Category::query()
            ->whereHas('products', function (Builder $query) use ($requestParams) {
                /** @var Product $query */
                $query->byBrands($requestParams['brands']);
                $query->byYear($requestParams['year_id']);
                $query->byAttributes($requestParams['attributes']);

                // Do not filter categories by selected category
            })
            ->get();

        // Get years, filter by products filtered by other params
        // Maybe we not need to filter at all, but within its better

        $years = Year::query()
            ->whereHas('values', function (Builder $query) use ($requestParams) {
                $query->whereHas('product', function (Builder $query) use ($requestParams) {
                    /** @var Product $query */
                    $query->byBrands($requestParams['brands']);
                    $query->byCategory($requestParams['category_id']);
                    $query->byAttributes($requestParams['attributes']);

                    // Do not filter years by selected year
                });
            })
            ->get();

        // Get available attributes (not just values), we can filter it by all another params

        $attributes = Attribute::query()
            ->whereHas('values', function (Builder $query) use ($requestParams) {
                $query->whereHas('product', function (Builder $query) use ($requestParams) {
                    /** @var Product $query */
                    $query->byBrands($requestParams['brands']);
                    $query->byCategory($requestParams['category_id']);
                    $query->byYear($requestParams['year_id']);
                    $query->byAttributes($requestParams['attributes']);
                });
            })
            ->where(function ($query) use ($requestParams) {
                $query->where('group_id', $requestParams['group_id']);
                $query->orWhereNull('group_id');
            })
            ->orderBy('order')
            ->get();

        // Only available options for attributes

        $attributesValuesQuery = AttributeValue::query()
            ->select('value', 'attribute_id')
            ->whereHas('product', function (Builder $query) use ($requestParams) {
                /** @var Product $query */
                $query->byBrands($requestParams['brands']);
                $query->byCategory($requestParams['category_id']);
            })
            ->where(function ($query) use ($requestParams) {
                /** @var Product $query */
                $query->byYear($requestParams['year_id']);
            });

        if ($requestParams['attributes']) {
            // When having attributes, we need to filter available attributes values only by other attribute values

            $attributesValuesQuery->where(function ($query) use ($attributes, $requestParams) {
                // To make it we need get all available attributes before and for each of them get values

                foreach ($attributes as $attribute) {
                    $query->orWhere(function ($query) use ($attribute, $requestParams) {
                        $query->where('attribute_id', $attribute->id); // Maybe not necessary?

                        $query->whereHas('product', function ($query) use ($requestParams, $attribute) {

                            // All attributes except current
                            /** @var Product $query */
                            $query->byAttributes(Arr::except($requestParams['attributes'], $attribute->id));
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

        if ($requestParams['category_id']) {
            /** @var Category $category */
            $category = Category::query()->find($requestParams['category_id']);

            if ($category) {
                $dynamicColumns = $dynamicColumns->merge($attributes->where('id', $category->main_attribute_id));
            }
        }

        if ($requestParams['group_id']) {
            $dynamicColumns = $dynamicColumns
                ->merge(
                    AttributeResource::collection(
                        $attributes->where('group_id', $requestParams['group_id'])
                    )
                );
        }

        return ProductResource::collection($productsQuery->paginate())->additional([
            'filters' => [
                'brands' => BrandResource::collection($brands),
                'categories' => CategoryResource::collection($categories),
                'years' => YearResource::collection($years),
                'attributes' => AttributeResource::collection($attributes->where('data_type', '!=', 'comment')),
            ],
            'request' => $requestParams,
            'dynamicColumns' => $dynamicColumns->sortBy(['groupBy', 'order'])
        ]);
    }
}
