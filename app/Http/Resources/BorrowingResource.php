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
            'is_overdue' => ($this->returned_at === null && $this->request->requestDetail->end_date < now()) 
                            || ($this->returned_at !== null && $this->returned_at > $this->request->details->last()->end_date),
            'status' => new BorrowingStatusResource($this->status),
            'request' => new BorrowingRequestResource($this->request),
        ];
    }
}
