<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $dateOfBirth = Carbon::parse($this->date_of_birth);
        
        return [
            'id' => $this->id,
            'name' => $this->name,
            'year_in' => $this->year_in,
            'date_of_birth' => $this->date_of_birth,
            'age' => $dateOfBirth->diff(Carbon::now())->y,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user' => new UserResource($this->whenLoaded('user')),
            'class' => new ClassResource($this->schoolClass),
        ];
    }
}
