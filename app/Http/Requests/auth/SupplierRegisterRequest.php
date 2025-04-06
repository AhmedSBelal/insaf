<?php

namespace App\Http\Requests\auth;

use Illuminate\Foundation\Http\FormRequest;

class SupplierRegisterRequest extends FormRequest
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
            'name'     => 'required|string|min:3|max:50',
            'email'    => 'required|string|email|unique:users,email',
            'location' => 'required|string|min:3|max:255',
            'phone_number' => 'required|string|min:3|max:255,unique:suppliers,phone_number',
            'commercial_register' => 'required|image|mimes:jpeg,png,jpg,heic|max:5120',
            'health_certificate' => 'required|image|mimes:jpeg,png,jpg,heic|max:5120',
        ];
    }
}
