<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Store extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => (int) $this->id,
            'name' => $this->name,
            'location' => $this->location,
            'description' => $this->description,
            'cellphone' => $this->cellphone,
            'category_id' => (int) $this->category_id,
            'created_at' => (string) $this->created_at
        ];
    }
}
