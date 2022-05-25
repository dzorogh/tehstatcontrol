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
use App\Stats\AttributesQuery;
use App\Stats\AttributeValueQuery;
use App\Stats\BrandsQuery;
use App\Stats\CategoriesQuery;
use App\Stats\ProductsQuery;
use App\Stats\RequestFilters;
use App\Stats\Sort;
use App\Stats\StatsByBrand;
use App\Stats\SubQuery;
use App\Stats\YearsQuery;
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
        $requestSort = new Sort($request, $requestFilters);


        $products = new ProductsQuery();
        $products->filter($requestFilters);

        // available params for each filter,
        // list of params must be filtered by products filtered by all other params except the param itself
        // Example: when filtered by year and brand, we must show only years available for products filtered by selected brand,
        // and only brands available for products filtered by selected year

        $brands = (new BrandsQuery())
            ->filter($requestFilters)
            ->get();

        $categories = (new CategoriesQuery())
            ->filter($requestFilters)
            ->get();

        $years = (new YearsQuery())
            ->filter($requestFilters)
            ->get();

        $attributes = (new AttributesQuery())
            ->filter($requestFilters)
            ->get();

        // Only available options for attributes

        $attributesValuesQuery = new AttributeValueQuery();
        $attributesValuesQuery->filter($requestFilters);

        if ($requestFilters->attributes) {
            // When having attributes, we need to filter available attributes values only by other attribute values

            $attributesValuesQuery->query->where(function ($query) use ($attributes, $requestFilters) {
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

    public function status()
    {
        return response([
            'started' => filled(cache("start_date")),
            'finished' => filled(cache("end_date")),
            'current_row' => (int) cache("current_row"),
            'total_rows' => (int) cache("total_rows"),
            'percent' => filled(cache("start_date")) ? (int) (cache("current_row") / cache("total_rows") * 100) : 0
        ]);
    }
}
