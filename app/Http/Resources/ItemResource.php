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
        // A function to check if the item is in the user cart
        $is_in_cart = function ($item) {
            $carts = auth()->user()->carts;
            foreach ($carts as $cart) {
                if ($cart->item_id === $item->id) {
                    return true;
                }
            }
            return false;
        };

        return [
            'id' => $this->id,
            'name' => $this->name,
            'stock' => $this->stock,
            'max_stock' => $this->max_stock,
            'image_url' => $this->image,
            'is_in_cart' => $is_in_cart($this),
            'category' => new ItemCategoryResource($this->category),
            'material' => new MaterialResource($this->material),
            'warehouse' => new WarehouseResource($this->warehouse),
        ];
    }
}
