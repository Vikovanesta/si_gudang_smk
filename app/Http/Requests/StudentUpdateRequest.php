<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StudentUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::user()->id === $this->student->user_id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'class_id' => 'nullable|int',
            'name' => 'nullable|string',
            'email' => 'nullable|string|email|unique:users,email,' . $this->student->user_id,
            'phone' => 'nullable|string',
            'nisn' => 'nullable|int|unique:students,nisn,',
            'year_in' => 'nullable|int',
            'date_of_birth' => 'nullable|date',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ];
    }
}
