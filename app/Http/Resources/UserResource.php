<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($this->role->name === 'student') {
            $profile = new StudentResource($this->student);
        }
        elseif ($this->role->name === 'laboran') {
            $profile = new LaboranResource($this->laboran);
        }
        elseif ($this->role->name === 'teacher') {
            $profile = new TeacherResource($this->teacher);
        }
        else {
            $profile = null;
        }

        return [
            'id' => $this->id,
            'email'=> $this->email,
            'phone' => $this->phone,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'is_verified' => $this->is_verified,
            'profile_image_url' => $this->profile_image,
            'role' => new RoleResource($this->role),
            'profile' => $profile,
        ];
    }
}
