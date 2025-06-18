<?php

namespace App\Http\Requests\API\Supplier;

use Illuminate\Foundation\Http\FormRequest;
use App\Traits\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreProductRequest extends FormRequest
{
    use ApiResponse;
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
        // dd();
        $rules =  [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:1',
            'expire_date' => 'required|date|after_or_equal:today',
            'images' => 'required|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ];

        $this->method() == 'PUT' ? $rules['images'] = 'nullable|array' : null;
        $this->method() == 'PUT' ? $rules['images.*'] = 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048' : null;

        return $rules;
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->failureResponse("Your data is invalid", 422, $validator->errors()));
    }
}
