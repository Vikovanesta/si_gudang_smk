<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class BorrowingRequestStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('academic');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'purpose' => 'required|string',
            'start_date' => 'required|date_format:Y-m-d H:i:s|after_or_equal:today',
            'end_date' => 'required|date_format:Y-m-d H:i:s|after:start_date',
            'borrowed_items' => 'required|json',
            'school_subject_id' => 'nullable|exists:school_subjects,id',
        ];
    }
}
