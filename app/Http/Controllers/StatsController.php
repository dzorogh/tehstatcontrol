<?php

namespace App\Http\Controllers;

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
use App\Stats\RequestParams;
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
            $groupId = Group::query()->first();
        }

        $attributes = [];

        if ($request->input('attributes')) {
            foreach ($request->input('attributes') as $index => $attributeParams) {
                if ($request->input("attributes.${index}.id") && $request->input("attributes.${index}.value")) {
                    $attributes[$request->input("attributes.${index}.id")] = $request->input("attributes.${index}.value");
                }
            }
        }

        $requestParams = new RequestParams();

        $requestParams->setBrands($request->input('brands'));
        $requestParams->setCategory($request->input('category'));
        $requestParams->setYear($request->input('year') ?? Year::query()->orderByDesc('value')->first()->id);
        $requestParams->setAttributes($attributes);
        $requestParams->setGroup($groupId);

        /*$requestParams = [
            // Brands is array, we can sort by multiply brands
            'brands' => $request->input('brands'),
            'category_id' => $request->input('category'),
            'year_id' => $request->input('year') ?? Year::query()->orderByDesc('value')->first()->id,
            'attributes' => $attributes,
            'group_id' => $groupId,
        ];*/

        $sort = [
            'type' => $request->input('sort.type', 'title'),
            'direction' => $request->input('sort.direction', 'asc'),
            'attributeId' => $request->input('sort.attributeId', null),
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
        $productsQuery->byBrands($requestParams->brands);
        $productsQuery->byCategory($requestParams->categoryId);
        $productsQuery->byYear($requestParams->yearId);
        $productsQuery->byAttributes($requestParams->attributes);


        // Next - available params for each filter,
        // list of params must be filtered by products filtered by all other params except the param itself
        // Example: when filtered by year and brand, we must show only years available for products filtered by selected brand,
        // and only brands available for products filtered by selected year

        // Get brands and filter by products

        $brands = new Brands($requestParams->categoryId, $requestParams->yearId, $requestParams->attributes);
        $brands = $brands->get();

        // Get categories (product types) and filter by filtered products

        $categories = new Categories($requestParams->brands, $requestParams->yearId, $requestParams->attributes);
        $categories = $categories->get();

        // Get years, filter by products filtered by other params
        // Maybe we not need to filter at all, but within its better

        $years = new Years($requestParams->categoryId, $requestParams->brands, $requestParams->attributes);
        $years = $years->get();

        // Get available attributes (not just values), we can filter it by all another params

        $attributes = Attribute::query()
            ->whereHas('values', function (Builder $query) use ($requestParams) {
                $query->whereHas('product', function (Builder $query) use ($requestParams) {
                    /** @var Product $query */
                    $query->byBrands($requestParams->brands);
                    $query->byCategory($requestParams->categoryId);
                    $query->byYear($requestParams->yearId);
                    $query->byAttributes($requestParams->attributes);
                });
            })
            ->where(function ($query) use ($requestParams) {
                $query->where('group_id', $requestParams->groupId);
                $query->orWhereNull('group_id');
            })
            ->orderBy('order')
            ->get();

        // Only available options for attributes

        $attributesValuesQuery = AttributeValue::query()
            ->select('value', 'attribute_id')
            ->whereHas('product', function (Builder $query) use ($requestParams) {
                /** @var Product $query */
                $query->byBrands($requestParams->brands);
                $query->byCategory($requestParams->categoryId);
            })
            ->where(function ($query) use ($requestParams) {
                /** @var Product $query */
                $query->byYear($requestParams->yearId);
            })
            ->whereHas('attribute', function (Builder $query) {
                $query->where('data_type', '!=', 'comment');
            });

        if ($requestParams->attributes) {
            // When having attributes, we need to filter available attributes values only by other attribute values

            $attributesValuesQuery->where(function ($query) use ($attributes, $requestParams) {
                // To make it we need get all available attributes before and for each of them get values

                foreach ($attributes as $attribute) {
                    $query->orWhere(function ($query) use ($attribute, $requestParams) {
                        $query->where('attribute_id', $attribute->id); // Maybe not necessary?

                        $query->whereHas('product', function ($query) use ($requestParams, $attribute) {

                            // All attributes except current
                            /** @var Product $query */
                            $query->byAttributes(Arr::except($requestParams->attributes, $attribute->id));
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

        if ($requestParams->categoryId) {
            /** @var Category $category */
            $category = Category::query()->find($requestParams->categoryId);

            if ($category) {
                $dynamicColumns = $dynamicColumns->merge($attributes->where('id', $category->main_attribute_id));
            }
        }

        if ($requestParams->groupId) {
            $dynamicColumns = $dynamicColumns
                ->merge(
                    AttributeResource::collection(
                        $attributes->where('group_id', $requestParams->groupId)
                    )
                );
        }

        // Stats by brand
        $chart = new StatsByBrand($productsQuery, $requestParams->groupId, $requestParams->yearId);
        $chart = $chart->get();

        $sorting = new Sort($productsQuery);
        $sorting->apply($sort['type'], $sort['direction'], $sort['attributeId']);

        return ProductResource::collection($productsQuery->paginate())->additional([
            'filters' => [
                'brands' => BrandResource::collection($brands),
                'categories' => CategoryResource::collection($categories),
                'years' => YearResource::collection($years),
                'attributes' => AttributeResource::collection($attributes->where('data_type', '!=', 'comment')),
            ],
            'request' => $requestParams->all(),
            'dynamicColumns' => $dynamicColumns->sortBy([['groupBy', 'desc'], 'order']),
            'chart' => $chart,
            'sort' => $sort
        ]);
    }
}
