<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BorrowingRequestDetailResource extends JsonResource
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
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'note' => $this->note,
            'is_revision' => $this->is_revision,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'status' => new BorrowingRequestStatusResource($this->status),
            'borrowed_items' => BorrowedItemResource::collection($this->borrowedItems),
        ];
    }
}
