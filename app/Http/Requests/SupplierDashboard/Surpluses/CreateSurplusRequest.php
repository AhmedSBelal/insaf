<?php

namespace App\Http\Requests\SupplierDashboard\Surpluses;

use Illuminate\Foundation\Http\FormRequest;

class CreateSurplusRequest extends FormRequest
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
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string',
            'description' => 'required|string',
            'quantity' => 'required|integer',
            'price' => 'required|integer',
            'expire_date' => 'required|date',
        ];
    }
}
