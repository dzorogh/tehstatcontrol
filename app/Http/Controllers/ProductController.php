<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductsRequest;
use App\Http\Resources\AttributeResource;
use App\Http\Resources\GroupResource;
use App\Http\Resources\ProductResource;
use App\Http\Resources\YearResource;
use App\Models\Attribute;
use App\Models\Group;
use App\Models\Product;
use App\Models\Year;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    public function getList(ProductsRequest $request): AnonymousResourceCollection
    {
        $productsQuery = Product::query()->with('brand', 'category', 'values');

        if ($request->has('ids')) {
            $productsQuery->whereIn('id', $request->validated('ids'));
        }

        $productsQuery->limit(50);

        $products = $productsQuery->get();

        $attributes = Attribute::query()->orderBy('order')->orderBy('title')->get();

        $years = Year::query()->orderBy('value')->get();

        return ProductResource::collection($products)->additional([
            'attributes' => AttributeResource::collection($attributes),
            'years' => YearResource::collection($years)
        ]);
    }
}
