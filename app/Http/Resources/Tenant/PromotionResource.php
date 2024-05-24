<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\JsonResource;

class PromotionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'description' => $this->description,
            'name' => $this->name,
            'status' => $this->status,
            'type'=> $this->type,
            'item_id'=> $this->item_id,
            'image' => $this->image,
            'image_url' => asset('storage'.DIRECTORY_SEPARATOR.'uploads'.DIRECTORY_SEPARATOR.'promotions'.DIRECTORY_SEPARATOR.$this->image),
        ];
    }
}