<?php

namespace App\Http\Resources;

use App\Models\Attribute;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Attribute
 */
class AttributeResource extends JsonResource
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
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'dataType' => $this->data_type,
            'byYear' => !!$this->by_year,
            'showOnChart' => !!$this->show_on_chart,
            'ratingDirection' => $this->rating_direction,
            'groupId' => $this->group_id,
            'group' => new GroupResource($this->whenLoaded('group')),
            'order' => $this->order,
            'values' => AttributeValueResource::collection($this->whenLoaded('values'))
        ];
    }
}
