<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Product
 */
class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $valuesCollection = collect([]);
        $brandValuesCollection = collect([]);

        if ($this->relationLoaded('values')) {
            $valuesCollection = $this->values;
        }

        if ($this->relationLoaded('brand') && $this->brand->relationLoaded('values')) {
            $brandValuesCollection = $this->brand->values;
        }

        $valuesResourceCollection = AttributeValueResource::collection(
            $valuesCollection->merge($brandValuesCollection)->keyBy('attribute_id')
        );

        $valuesResourceCollection->preserveKeys = true;


        return [
            'id' => $this->id,
            'title' => $this->title,
//            'brandId' => $this->brand_id,
//            'categoryId' => $this->category_id,
            'brand' => new BrandResource(
                $this->whenLoaded('brand')
            ),
            'category' => new CategoryResource(
                $this->whenLoaded('category')
            ),
            'valuesByAttributeId' => $valuesResourceCollection,
        ];
    }
}
