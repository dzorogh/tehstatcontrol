<?php

namespace App\Http\Resources\Stats;

use App\Models\Year;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Year
 */
class YearResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'value' => $this->value
        ];
    }
}
