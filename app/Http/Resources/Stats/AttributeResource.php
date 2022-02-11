<?php

namespace App\Http\Resources\Stats;

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
            'dataType' => $this->data_type,
            'byYear' => !!$this->by_year,
            'showOnChart' => !!$this->show_on_chart,
            'groupId' => $this->group_id,
            'group' => new GroupResource($this->whenLoaded('group')),
            'values' => AttributeValueResource::collection($this->whenLoaded('values'))
        ];
    }
}
