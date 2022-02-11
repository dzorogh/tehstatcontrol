<?php

namespace App\Http\Resources\Stats;

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
        $values = AttributeValueResource::collection(
            $this->whenLoaded('values')->keyBy('attribute_id')
        );

        $values->preserveKeys = true;

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
            'valuesByAttributeId' => $values,
        ];
    }
}
