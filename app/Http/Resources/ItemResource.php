<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'stock' => $this->stock,
            'max_stock' => $this->max_stock,
            'image_url' => $this->image,
            'material' => new MaterialResource($this->material),
            'warehouse' => new WarehouseResource($this->warehouse),
        ];
    }
}
