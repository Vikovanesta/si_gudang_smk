<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BorrowingRequestResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $sortedDetails = $this->details->sortByDesc('id');

        return [
            'id' => $this->id,
            'purpose' => $this->purpose,
            'status' => $sortedDetails->first()->status->name ?? 'pending',
            'is_revised' => $this->is_revised,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'details' => BorrowingRequestDetailResource::collection($sortedDetails),
            'sender' => new UserResource($this->sender),
            'handler' => new UserResource($this->handler),
        ];
    }
}
