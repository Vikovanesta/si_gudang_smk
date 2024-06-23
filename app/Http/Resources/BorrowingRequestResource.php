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
        $status = $sortedDetails->first()->status->name ?? 'pending';
        if ($status === 'pending' && $this->is_revised) {
            $status = 'revised';
        }

        return [
            'id' => $this->id,
            'purpose' => $this->purpose,
            'status' => $status,
            'is_revised' => $this->is_revised,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'borrowed_items_count' => $sortedDetails->first()->borrowedItems->count(),
            'details' => BorrowingRequestDetailResource::collection($sortedDetails),
            'sender' => new UserResource($this->sender),
            'handler' => new UserResource($this->handler),
            'school_subject' => new SubjectResource($this->schoolSubject),
        ];
    }
}
