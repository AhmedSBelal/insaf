<?php

namespace App\Http\Requests\APP\Surpluses;

use Illuminate\Foundation\Http\FormRequest;

class SurplusSearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'quantity' => 'nullable|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'expire_date' => 'nullable|date|after_or_equal:today',
            'page' => 'nullable|integer|min:1',
            'category' => 'nullable|integer|min:1|exists:categories,id',
        ];
    }

    public function messages()
    {
        return [
            'expire_date.after_or_equal' => 'The expire date must be today or a future date.',
            'quantity.integer' => 'Quantity must be a valid integer.',
            'price.numeric' => 'Price must be a valid number.',
        ];
    }

}
