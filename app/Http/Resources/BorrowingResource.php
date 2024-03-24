<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BorrowingResource extends JsonResource
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
            'borrowed_at' => $this->borrowed_at,
            'returned_at' => $this->returned_at,
            'note' => $this->note,
            'status' => new BorrowingStatusResource($this->status),
            'request' => new BorrowingRequestResource($this->request),
        ];
    }
}
