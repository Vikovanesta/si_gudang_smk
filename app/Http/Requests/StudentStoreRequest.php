<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StudentStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('management');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'class_id' => 'required|int',
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:8',
            'phone' => 'required|string',
            'nisn' => 'required|int|unique:students,nisn',
            'year_in' => 'required|int',
            'date_of_birth' => 'required|date_format:Y-m-d',
            'profile_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ];
    }
}
