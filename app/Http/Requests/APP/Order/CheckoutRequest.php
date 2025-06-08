<?php

namespace App\Http\Requests\APP\Order;

use App\Enums\PaymentMethodes;
use App\Enums\UserRoles;
use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasRole(UserRoles::Charity->value);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'payment_method' => 'required|string|in:' . implode(',', array_map(fn($case) => $case->value, PaymentMethodes::cases())),
        ];
    }
}
