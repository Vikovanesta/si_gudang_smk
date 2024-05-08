<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class BorrowedItemUpdateRequest extends FormRequest
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
            'returned_quantity' => 'nullable|integer|min:0|max:' . ($this->borrowedItem->quantity - $this->borrowedItem->returned_quantity),
            'is_cancelled' => 'nullable|boolean|missing_with:is_borrowed',
            'is_borrowed' => 'nullable|boolean|missing_with:is_cancelled,borrowed_at',
            'borrowed_at' => 'nullable|date_format:Y-m-d H:i:s',
            'returned_at' => 'nullable|date_format:Y-m-d H:i:s|after:borrowed_at',
        ];
    }
}
