<?php

namespace App\Http\Resources;

use App\Models\BorrowingStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BorrowedItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        if ($this->requestDetail->status->name == 'approved') {
            return [
                'id' => $this->id,
                'quantity' => $this->quantity,
                'returned_quantity' => $this->returned_quantity,
                'borrowed_at' => $this->borrowed_at,
                'returned_at' => $this->returned_at,
                'is_cancelled' => $this->is_cancelled,
                'status' => new BorrowingStatusResource($this->status),
                'item' => new ItemResource($this->item),
            ];
        }
        else {
            return [
                'id' => $this->id,
                'quantity' => $this->quantity,
                'item' => new ItemResource($this->item),
            ];
        }
    }
}
