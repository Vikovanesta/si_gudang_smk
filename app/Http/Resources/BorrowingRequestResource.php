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
            'details' => BorrowingRequestDetailResource::collection($sortedDetails),
        ];
    }
}
