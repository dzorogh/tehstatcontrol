<?php

namespace App\Http\Resources;

use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin AttributeValue
 */
class AttributeValueResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'attribute' => new AttributeResource(
                $this->whenLoaded('attribute')
            ),

            'year' => new YearResource(
                $this->whenLoaded('year')
            ),

            'value' => $this->value,
            'id' => $this->id,

//            'yearId' => $this->year_id,
            'attributeId' => $this->attribute_id,
        ];
    }
}
