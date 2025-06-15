<?php

namespace App\Http\Requests\API\Supplier;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use App\Traits\ApiResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class UpdatePasswordRequest extends FormRequest
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
        return [
            'current_password' => 'required|string',
            'new_password' => [Password::min(8)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()
                ->uncompromised(), 'required', 'confirmed'],

            'new_password_confirmation' => 'required|string',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->failureResponse("Your data is invalid", 422, $validator->errors()));
    }
}
