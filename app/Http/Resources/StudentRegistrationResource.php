<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentRegistrationResource extends JsonResource
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
            'email' => $this->email,
            'phone' => $this->phone,
            'nisn' => $this->nisn,
            'year_in' => $this->year_in,
            'date_of_birth' => $this->date_of_birth,
            'is_verified' => $this->is_verified,
            'verified_at' => $this->verified_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'class' => new ClassResource($this->schoolClass),
            'verifier' => new UserResource($this->verifier),
        ];
    }
}
